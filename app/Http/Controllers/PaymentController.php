<?php

namespace App\Http\Controllers;

use Cache;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth,Validator,Session,Redirect,Input,URL;
use App\Models\User;
use App\Models\SettingBarCode;
use App\Models\SettingEmail;
use App\Models\PaymentHistory;
use App\Helpers\BatvHelper;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

use Yajra\Datatables\Datatables;

class PaymentController extends Controller
{
    private $_api_context;
    private $messages;
    private $settingEmail;

    public function __construct(){
    	parent::__construct();
        $this->_api_context = new ApiContext(new OAuthTokenCredential(config('paypal.client_id'),config('paypal.secret')));
        $this->_api_context->setConfig(config('paypal.settings'));

        $this->messages = Cache::remember('messages', 1440, function() {
            return \DB::table('messages')->where('category', 3)->pluck('message', 'name');
        });

        $this->settingEmail = Cache::remember('SettingEmail', 1440, function() {
            return \DB::table('setting_email')->get();
        });
    }


    public function getPayment(Request $request){
    	$setting_barcode = SettingBarCode::get();
        $info_account = User::findOrFail(Auth::user()->id);
        return view('layouts_frontend.payment.add',['setting_barcode'=>$setting_barcode,'info_account'=>$info_account]);
    }

    public function getPaymentHistory(Request $request){
    	return view('layouts_frontend.payment.payment_history_tab');
    }

    public function paymentHistoryAjax(Request $request) {
        return Datatables::of(
            PaymentHistory::listPaymentHistory($request)
                )->addColumn('all', function ($payment) {
                })->removeColumn('id')->make(true);
    }

    public function paymentConfirm(Request $request){
		$info_account = User::findOrFail(Auth::user()->id);

		Session::set('price', $request->amount);
		$number = SettingBarCode::where('price',$request->amount)->value('number');
		Session::set('number', $number);
    	
    	$data = ['price'=>Session::get('price'),'number'=>Session::get('number')];
    	// print_r($data);die;
    	return view('layouts_frontend.payment.confirm',['info_account'=>$info_account,'data'=>$data]);
    }

    public function postPaymentConfirm(Request $request)
    {
    	// echo 2;die;
    	$price = Session::get('price');
    	$price = (int)$price;
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Barcode') /** item name **/
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($price); /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($price);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('payment.status')) /** Specify return URL **/
            ->setCancelUrl(URL::route('payment.status'));
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
            /** dd($payment->create($this->_api_context));exit; **/
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error','Connection timeout');
                return Redirect::route('getPayment');
                /** echo "Exception: " . $ex->getMessage() . PHP_EOL; **/
                /** $err_data = json_decode($ex->getData(), true); **/
                /** exit; **/
            } else {
                \Session::put('error','Some error occur, sorry for the inconvenience');
                return Redirect::route('getPayment');
                /** die('Some error occur, sorry for inconvenient'); **/
            }
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        if(isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        \Session::put('error','Unknown error occurred');
        return Redirect::route('getPayment');
    }

    public function getPaymentStatus()
    {
        try{
            /** Get the payment ID before session clear **/
            $payment_id = Session::get('paypal_payment_id');
            /** clear the session payment ID **/
            Session::forget('paypal_payment_id');
            if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
                \Session::put('error','Payment failed');
                return Redirect::route('getPayment');
            }
            $payment = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId(Input::get('PayerID'));
            $result = $payment->execute($execution, $this->_api_context);

            if ($result->getState() == 'approved') { 
                $user_id = Auth::user()->id;
                $price = Session::get('price');
                $price = (int)$price;
                $number = Session::get('number');
                $number = (int)$number;
                //Lưu lịch sử thanh toán
                $item = new PaymentHistory;
                $item->user_id = $user_id ;
                $item->order_name =  $payment_id;
                $item->price =  $price;
                $item->amount = $number; 
                $item->created_at  =  date('Y-m-d H:i:s');
                $item->save();
                // Tăng số lượng barcode nếu giao dịch thành công
                User::where('id',$user_id)->increment('number_barcode', $number);

                // send email                
                $index = array_search('payment.create',array_column($this->settingEmail, 'function'));
                $checkSendEmail = $this->settingEmail[$index];

                if($checkSendEmail->user == 1){
                    $template = 'layouts_frontend.email.to_user.create_payment_success';
                    $title = 'Your transaction is successful.';
                    $email = Auth::user()->email;
                    $content_mail = array(
                        'payment' =>  $payment_id
                    );

                    BatvHelper::sendEmail($template, $title, $email, $content_mail);
                }

                if($checkSendEmail->admin == 1){
                    $template = 'layouts_frontend.email.to_admin.create_payment_success';
                    $title = Auth::user()->name .'\'s transaction is successful.';
                    $email = env('MAIL_USERNAME', 'nhansu@tohsoft.com');
                    $content_mail = array(
                        'payment'   =>  $payment_id,
                        'user'      =>  Auth::user()->name
                    );

                    BatvHelper::sendEmail($template, $title, $email, $content_mail);
                }
                // end send email

                return redirect()->route('getPayment')->with(['flash_message_succ' => isset($this->messages['payment.success']) ? $this->messages['payment.success'] : 'Payment success']);
            }
            return redirect()->route('getPayment')->with(['flash_message_err' => isset($this->messages['payment.error']) ? $this->messages['payment.error'] : 'Payment failed']);
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }

    public function putStatePaymentTable(Request $request){
        try {
            if(isset($request['data'])){
                $user = Auth::user();
                $user->payment_table_setting = $request['data'];
                if($user->save()){
                    return response()->json(["Response"=>"Success","Message"=>"The data has been saved"]);
                }
                return response()->json(["Response"=>"Error","Message"=>"An error occurred during save process, please try again"]);
            }
        } catch (Exception $e) {
            return response()->json(["Response"=>"Error","Message"=>"An error occurred during save process, please try again"]);
        }
    }

}


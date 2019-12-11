<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\BarCode;
use App\Models\ImageBarCode;
use App\Models\SettingPriceBarCode;
use App\Models\User;
use App\Models\Message;
use App\Models\SettingEmail;
use Auth;
use Cache;
use Validator;
use Tracker;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;
use App\Mylibs\ResizeImage;
use App\Helpers\BatvHelper;

class BarCodeController extends Controller
{
    private $messages;
    private $settingEmail;

    public function __construct()
    {
        $this->messages = Cache::remember('messages', 1440, function() {
            return \DB::table('messages')->where('category', 1)->pluck('message', 'name');
        });

        $this->settingEmail = Cache::remember('SettingEmail', 1440, function() {
            return \DB::table('setting_email')->get();
        });
    }

    public function listBarCodebyUser(Request $request){
        $id = Auth::user()->id;
        if( $request->id == $id ){
            $numberBarCode_unpaid = BarCode::countBarCodebyUserUnpaid($id);
            $priceBarCode = SettingPriceBarCode::getPriceBarCode();
            $arr = BarCode::listBarCodebyUserDefault($request);
            $messages_delete_barcode = isset($this->messages['barcode.alert.delete']) ? $this->messages['barcode.alert.delete'] : 'Are you sure you want to delete selected products in this page?';
            $messages_check_buy_barcode = isset($this->messages['barcode.run_out_of_free']) ? $this->messages['barcode.run_out_of_free'] : 'You do not have any product creations, please buy more!';
            $messages_check_selected_delete = isset($this->messages['barcode.alert.check_selected_delete']) ? $this->messages['barcode.alert.check_selected_delete'] : 'Nothing selected! Please select some products to delete.';

            return view('layouts_frontend.barcode.index',['arr'=>$arr,'numberBarCode_unpaid'=>$numberBarCode_unpaid,'priceBarCode'=>$priceBarCode,'messages_delete_barcode'=>$messages_delete_barcode,'messages_check_buy_barcode'=>$messages_check_buy_barcode,'messages_check_selected_delete'=>$messages_check_selected_delete]);
        }else{
            return redirect()->route('listBarCodebyUser', ['id' => $id]);
        }
    }


    public function getBarCodeAddbyUser(){
        return view('layouts_frontend.barcode.add');
    }

    public function postBarCodeAddbyUser(Request $request){
        $checkValidate = true;
        $time_created = date('Y-m-d H:i:s');
        try{
            // Kiểm tra xem User có thể tạo Barcode được nữa ko
            $check = User::checkBarCodebyUser();
            if( $check ){
                $user_id = Auth::user()->id;
                if( $request->file_excel ){
                    $rules = array(
                        'file_excel' => 'required',
                    );
                    $messages = [
                        'file_excel.required'=> isset($this->messages['barcode.file_excel.required']) ? $this->messages['barcode.file_excel.required'] : 'The file field is required.',
                        // 'file_excel.mimes'=> isset($this->messages['barcode.file_excel.mimes']) ? $this->messages['barcode.file_excel.mimes'] : 'The File is not formatted correctly.',
                    ];

                    $validator = Validator::make($request->all(), $rules);

                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator);
                    }else{
                        // add check for file is too large. only get first row to check invalid data
                        ini_set('memory_limit', '2048M');
                        $returnCheck = \Excel::load($request->file_excel)->skipRows(1)->takeRows(1)->get();
                        if(!method_exists($returnCheck, 'getHeading')){
                            return back()->with(['flash_message_err_and_reload' => 'The File is not formatted correctly']);
                        }else{
                            $dataCheck = $returnCheck->getHeading();
                            if(!(in_array('ordinal_number',$dataCheck, TRUE) && 
                                in_array('barcode',$dataCheck, TRUE) && 
                                in_array('name',$dataCheck, TRUE) && 
                                in_array('model',$dataCheck, TRUE) && 
                                in_array('manufacturer',$dataCheck, TRUE) && 
                                in_array('average_price',$dataCheck, TRUE) && 
                                in_array('currency_unit',$dataCheck, TRUE) && 
                                in_array('currency_unit',$dataCheck, TRUE))){
                                return back()->with(['flash_message_err_and_reload' => 'The File is not formatted correctly']);
                            }

                            // if file is ok, then load all data and process
                            $results = \Excel::load($request->file_excel);
                            $data = $results->toArray();

                            if (count($data) > 0) {
                                // Kiểm tra xem file có đúng định dạng như file Example ko
                                if (isset($data[0]['ordinal_number']) && isset($data[0]['barcode']) && isset($data[0]['name']) && isset($data[0]['model']) && isset($data[0]['manufacturer']) && isset($data[0]['average_price']) && isset($data[0]['currency_unit'])  && isset($data[0]['image'])) {
                                    $list_barcode = [];
                                    foreach ($data as $row) {
                                        unset($rules);
                                        unset($validator);  
                                        $barcode = trim($row['barcode']);
                                        $rules = [
                                            'barcode' =>'required|numeric|digits_between:12,13|check_barcode:'.$barcode,
                                            'name' =>'required|max:200',
                                            'model' =>'required|max:50',
                                            'manufacturer' => 'required|max:200',
                                            'avg_price' => 'required|numeric|min:0',
                                            'currency_unit' => 'validate_currency_unit:'.$row['currency_unit'],
                                        ];

                                        $messages = [
                                            'barcode.check_barcode'=> isset($this->messages['barcode.check_barcode']) ? $this->messages['barcode.check_barcode'] : 'The product has already been taken.',
                                            'avg_price.numeric'=> isset($this->messages['barcode.avg_price.numeric']) ? $this->messages['barcode.avg_price.numeric'] : 'The avg price should be a number.',
                                            'currency_unit.validate_currency_unit'=> isset($this->messages['barcode.currency_unit.validate_currency_unit']) ? $this->messages['barcode.currency_unit.validate_currency_unit'] : 'Currency unit invalid.',
                                        ];
                                        $tmp = [
                                                    'barcode'=>$barcode,
                                                    'name'=>$row['name'],
                                                    'model'=>$row['model'],
                                                    'manufacturer'=>$row['manufacturer'],
                                                    'avg_price'=>$row['average_price'],
                                                    'currency_unit'=>$row['currency_unit'],
                                                 ];
                                        $validator = Validator::make($tmp, $rules,$messages);
                                        if ($validator->fails()) {
                                            return redirect()->back()->withErrors($validator);
                                        } else {
                                            include_once(public_path('libs/PHPCrawler.class.php'));
                                            include_once(public_path('libs/simple_html_dom.php'));
                                            libxml_use_internal_errors(true);

                                            $arrContextOptions = array(
                                                "ssl" => array(
                                                    "verify_peer" => false,
                                                    "verify_peer_name" => false,
                                                ),
                                            );

                                            $str_image = '';
                                            $arr_file = explode(',', $row['image']);
                                            $arr_file = array_unique($arr_file);

                                            foreach ($arr_file as $file) {
                                                $link_img = trim($file);

                                                if (!(empty($link_img)) && strpos($link_img, "http") !== false) {
                                                    $contentImage = @file_get_contents($link_img, false, stream_context_create($arrContextOptions));
                                                    if ($contentImage !== FALSE) {
                                                        $ext = 'png';
                                                        $fileName = round(microtime(true) * 1000) . '.' . $ext;
                                                        $str_image .= $fileName . ',';
                                                        $fp2 = fopen(public_path('uploads/barcode/') . $fileName, "w");
                                                        fwrite($fp2, $contentImage);
                                                        fclose($fp2);
                                                    }
                                                }
                                            }
                                            // else{
                                            //     $fileName = $row['image'];
                                            // }

                                            $arr[]= [
                                                        'barcode'=>$barcode,
                                                        'name'=>$row['name'],
                                                        'model'=>$row['model'],
                                                        'manufacturer'=>$row['manufacturer'],
                                                        'avg_price'=>$row['average_price'],
                                                        'currency_unit'=>$row['currency_unit'],
                                                        'spec'=>$row['specification'],
                                                        'feature'=>$row['feature'],
                                                        'description'=>$row['description'],
                                                        'image'=> rtrim($str_image, ","),
                                                        'created_at'=>$time_created,
                                                        'updated_at'=>$time_created,
                                                        'user_id'=>$user_id,
                                                    ];

                                            // Trường hợp barcode duplicate thì báo lỗi
                                            $duplicate = (in_array($barcode, $list_barcode)) ? true : false ;

                                            if ($duplicate == true) {
                                                return back()->with(['flash_message_err_and_reload' => isset($this->messages['barcode.duplicate']) ? $this->messages['barcode.duplicate'] : 'The products have been duplicated in file excel.']);
                                        }

                                        $list_barcode[] = $barcode;
                                    }
                                }

                                    if(  count($arr)>0 ){
                                        $checkMulti = User::checkBarCodebyUser(count($arr));
                                        if( $checkMulti ){
                                            BarCode::insert($arr);
					BatvHelper::insertMultiBarcode($arr);
                                            User::where('id', $user_id)->update( ['number_barcode'=> (Auth::user()->number_barcode - count($arr) ) ] );

                                            $index = array_search('barcode.create',array_column($this->settingEmail, 'function'));
                                            $checkSendEmail = $this->settingEmail[$index];

                                            if($checkSendEmail->user == 1){
                                                $template = 'layouts_frontend.email.to_user.create_barcodes_success';
                                                $title = 'The products have been created.';
                                                $email = Auth::user()->email;
                                                $content_mail = array(
                                                    'barcode' =>  $barcode
                                                );

                                                BatvHelper::sendEmail($template, $title, $email, $content_mail);
                                            }

                                            if($checkSendEmail->admin == 1){
                                                $template = 'layouts_frontend.email.to_admin.create_barcodes_success';
                                                $title = 'Some product of '. Auth::user()->name .' have been created.';
                                                $email = env('MAIL_USERNAME', 'nhansu@tohsoft.com');
                                                $content_mail = array(
                                                    'barcode'   =>  $barcode,
                                                    'user'      =>  Auth::user()->name
                                                );

                                                BatvHelper::sendEmail($template, $title, $email, $content_mail);
                                            }
                                            
                                            return redirect()->route('listBarCodebyUser', ['id' => $user_id])->with(['flash_message_succ' => isset($this->messages['barcode.create_susscess']) ? $this->messages['barcode.create_susscess'] : 'The product has been created.']);
                                        }else{
                                            return back()->with(['flash_message_err_special'=>isset($this->messages['barcode.run_out_of_free']) ? $this->messages['barcode.run_out_of_free'] : 'You have run out of barcodes.']);
                                        }
                                    }
                                } else {
                                    return back()->with(['flash_message_err_and_reload' => isset($this->messages['barcode.file_excel.mimes']) ? $this->messages['barcode.file_excel.mimes'] : 'The products have been duplicated in file excel.']);
                                }
                            } else {
                                return back()->with(['flash_message_err' => isset($this->messages['barcode.file_excel.empty']) ? $this->messages['barcode.file_excel.empty'] : 'The file cannot be empty.']);
                            }
                        }
                    } 
                } else {
                    return back()->with(['flash_message_err' => isset($this->messages['barcode.file_excel.required']) ? $this->messages['barcode.file_excel.required'] : 'The file field is required.']);
                }
            }else{
                return redirect()->back()->with(['flash_message_err_special'=>isset($this->messages['barcode.run_out_of_free']) ? $this->messages['barcode.run_out_of_free'] : 'You have run out of barcodes.']);
            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }

    public function droponeJs(Request $request)
    {

        $files = $request->file('files');
        // echo '<pre>';
        // print_r($request->files);die;

        if ($files) {
            $destination_path = public_path('uploads/barcode/');
            $data = [];

            foreach ($files as $file_object) {
                if ($file_object->getSize() > 0) {
                    $file_name = $file_object->getClientOriginalName();
                    $ext  = \File::extension($file_name);
                    $ext  = strtolower($ext);
                    $file_name = str_replace("." . $ext, "", $file_name);
                    $file_name = str_slug($file_name, '-');
                    // $destination_file_name = str_slug($file_name, '_') . round(microtime(true) * 1000) . '.' . $ext;
                    $destination_file_name = round(microtime(true) * 1000) . $file_name . '.' . $ext;
                    $destination_file_name = ltrim($destination_file_name, ',');
                    $file_object->move($destination_path, $destination_file_name);
                    $data[] = $destination_file_name;
                }
            }
        }

        return response()->json(array('status' => 200 , 'data' => $data));
    }

    public function addBarCodeNormalByUserAjax(Request $request){
        // dd($request);
        try{
            // Kiểm tra xem User có thể tạo Barcode được nữa ko
            $check = User::checkBarCodebyUser();
            if( $check ){
                $user_id = Auth::user()->id;
                $rules = [
                    'barcode' =>'required|numeric|digits_between:12,13|check_barcode:'.$request->barcode.'|regex:/(^(\d+)?$)/u',
                    'name' =>'required|max:200',
                    'model' =>'required|max:50',
                    'manufacturer' => 'required|max:200',
                    'avg_price' => 'required|numeric|min:0',
                ];
                $messages = [
                    'barcode.required'=> isset($this->messages['barcode.required']) ? $this->messages['barcode.required'] : 'The product field is required.',
                    'barcode.numeric'=> isset($this->messages['barcode.numeric']) ? $this->messages['barcode.numeric'] : 'The product should be a number.',
                    'barcode.digits_between'=> isset($this->messages['barcode.digits_between']) ? $this->messages['barcode.digits_between'] : 'The product must be between 12 and 13 digits.',
                    'barcode.check_barcode'=> isset($this->messages['barcode.check_barcode']) ? $this->messages['barcode.check_barcode'] : 'The product has already been taken.',

                    'name.required'=> isset($this->messages['barcode.name.required']) ? $this->messages['barcode.name.required'] : 'The name field is required.',
                    'name.max'=> isset($this->messages['barcode.name.max']) ? $this->messages['barcode.name.max'] : 'The name may not be greater than 200 characters.',
                    'model.required'=> isset($this->messages['barcode.model.required']) ? $this->messages['barcode.model.required'] : 'The model field is required.',
                    'model.max'=> isset($this->messages['barcode.model.max']) ? $this->messages['barcode.model.max'] : 'The model may not be greater than 50 characters.',
                    'manufacturer.required'=> isset($this->messages['barcode.manufacturer.required']) ? $this->messages['barcode.manufacturer.required'] : 'The manufacturer field is required.',
                    'manufacturer.max'=> isset($this->messages['barcode.manufacturer.max']) ? $this->messages['barcode.manufacturer.max'] : 'The manufacturer may not be greater than 200 characters.',
                    'avg_price.required'=> isset($this->messages['barcode.avg_price.required']) ? $this->messages['barcode.avg_price.required'] : 'The avg price field is required.',
                    'avg_price.min'=> isset($this->messages['barcode.avg_price.min']) ? $this->messages['barcode.avg_price.min'] : 'The avg price must be at least 0.',
                    'avg_price.numeric'=> isset($this->messages['barcode.avg_price.numeric']) ? $this->messages['barcode.avg_price.numeric'] : 'The avg price should be a number.',
                ];

                $validator = Validator::make($request->all(), $rules, $messages);
                \Session::flash('check_in','add_normal');
                if ($validator->fails()) {
                    $arr_1 = $validator->errors()->keys();
                    $arr_2 = $validator->errors()->all();

                    foreach ($arr_1 as $key => $value) {
                        $arr[$value] = $arr_2[$key];
                    }

                    return response()->json(['error'=>$arr]);
                }else{
                    $dateCurrent = date('Y-m-d H:i:s');
                    $item = new BarCode;
                    $item->barcode =  trim($request->barcode);
                    $item->name =  $request->name;
                    $item->image = rtrim($request->str_image, ",");
                    $item->model =  $request->model;
                    $item->manufacturer  =  $request->manufacturer;
                    $item->avg_price = str_replace('+','',$request->avg_price);
                    $item->currency_unit =  $request->currency_unit;
                    $item->spec =  $request->spec;
                    $item->feature =  $request->feature;
                    $item->description =  $request->description;
                    $item->created_at  =  $dateCurrent;
                    $item->updated_at  =  $dateCurrent;
                    $item->user_id = $user_id;
                    $item->save();

                    BatvHelper::insertBarcode($item);
                    User::where('id', $user_id)->update( ['number_barcode'=> (Auth::user()->number_barcode - 1 ) ] );
                    \Session::flash('check_in','add_normal');

                    // $checkSendEmail = SettingEmail::where('function', 'barcode.create')->first();

                    $index = array_search('barcode.create',array_column($this->settingEmail, 'function'));
                    $checkSendEmail = $this->settingEmail[$index];

                    if($checkSendEmail->user == 1){
                        $template = 'layouts_frontend.email.to_user.create_barcode_success';
                        $title = 'The product '. trim($request->barcode) .' has been created.';
                        $email = Auth::user()->email;
                        $content_mail = array(
                            'barcode' =>  trim($request->barcode)
                        );

                        BatvHelper::sendEmail($template, $title, $email, $content_mail);
                    }

                    if($checkSendEmail->admin == 1){
                        $template = 'layouts_frontend.email.to_admin.create_barcode_success';
                        $title = 'A barcode of '. Auth::user()->name .' have been created.';
                        $email = env('MAIL_USERNAME', 'nhansu@tohsoft.com');
                        $content_mail = array(
                            'barcode'   =>  trim($request->barcode),
                            'user'      =>  Auth::user()->name
                        );

                        BatvHelper::sendEmail($template, $title, $email, $content_mail);
                    }

                    return response()->json(['success'=>isset($this->messages['barcode.create_susscess']) ? $this->messages['barcode.create_susscess'] : 'The product has been created.','user_id'=>$user_id]);
                }

            }else{
                return response()->json(['error'=>isset($this->messages['barcode.run_out_of_free']) ? $this->messages['barcode.run_out_of_free'] : 'You do not have any barcode creations, please buy more!']);
            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }

    public function getBarCodeEditbyUser($id){
        $data = BarCode::listBarCodebyId($id,Auth::user()->id);
        return view('layouts_frontend.barcode.edit',['data'=>$data]);
    }

    public function getBarCode($id){
        $data = BarCode::getBarCodebyId($id);
        // dd($data);
        return view('layouts_frontend.barcode.view',['data'=>$data]);
    }

    public function putStateBarcodeTable(Request $request){
        try {
            if(isset($request['data'])){
                $user = Auth::user();
                $user->barcode_table_setting = $request['data'];
                if($user->save()){
                    return response()->json(["Response"=>"Success","Message"=>"The data has been saved"]);
                }
                return response()->json(["Response"=>"Error","Message"=>"An error occurred during save process, please try again"]);
            }
        } catch (Exception $e) {
            return response()->json(["Response"=>"Error","Message"=>"An error occurred during save process, please try again"]);
        }
    }

    public function putBarCodeEditbyUser(Request $request){
        $itemUpdate = Barcode::find($request->id);

        if($itemUpdate->barcode == trim($request->barcode)){
            $rules = [
                'barcode' =>'required|numeric|digits_between:12,13|min:0|unique:barcode,barcode,'.$request->id,
                'name' =>'required|max:200',
                'model' =>'required|max:50',
                'manufacturer' => 'required|max:200',
                'avg_price' => 'required|numeric|min:0',
            ];
        }else{
            $rules = [
                'barcode' =>'required|numeric|digits_between:12,13|min:0|unique:barcode,barcode,'.$request->id.'|check_barcode:'.$request->barcode,
                'name' =>'required|max:200',
                'model' =>'required|max:50',
                'manufacturer' => 'required|max:200',
                'avg_price' => 'required|numeric|min:0',
            ];
        }

        $messages = [
            'barcode.required'=> isset($this->messages['barcode.required']) ? $this->messages['barcode.required'] : 'The product field is required.',
            'barcode.numeric'=> isset($this->messages['barcode.numeric']) ? $this->messages['barcode.numeric'] : 'The product should be a number.',
            'barcode.digits_between'=> isset($this->messages['barcode.digits_between']) ? $this->messages['barcode.digits_between'] : 'The product must be between 12 and 13 digits.',
            'barcode.unique'=> isset($this->messages['barcode.unique']) ? $this->messages['barcode.unique'] : 'The product has already been taken.',
            'barcode.check_barcode'=> isset($this->messages['barcode.check_barcode']) ? $this->messages['barcode.check_barcode'] : 'The product has already been taken.',

            'name.required'=> isset($this->messages['barcode.name.required']) ? $this->messages['barcode.name.required'] : 'The name field is required.',
            'name.max'=> isset($this->messages['barcode.name.max']) ? $this->messages['barcode.name.max'] : 'The name may not be greater than 200 characters.',
            'model.required'=> isset($this->messages['barcode.model.required']) ? $this->messages['barcode.model.required'] : 'The model field is required.',
            'model.max'=> isset($this->messages['barcode.model.max']) ? $this->messages['barcode.model.max'] : 'The model may not be greater than 50 characters.',
            'manufacturer.required'=> isset($this->messages['barcode.manufacturer.required']) ? $this->messages['barcode.manufacturer.required'] : 'The manufacturer field is required.',
            'manufacturer.max'=> isset($this->messages['barcode.manufacturer.max']) ? $this->messages['barcode.manufacturer.max'] : 'The manufacturer may not be greater than 200 characters.',
            'avg_price.required'=> isset($this->messages['barcode.avg_price.required']) ? $this->messages['barcode.avg_price.required'] : 'The avg price field is required.',
            'avg_price.min'=> isset($this->messages['barcode.avg_price.min']) ? $this->messages['barcode.avg_price.min'] : 'The avg price must be at least 0.',
            'avg_price.numeric'=> isset($this->messages['barcode.avg_price.numeric']) ? $this->messages['barcode.avg_price.numeric'] : 'The avg price should be a number.',
        ];
        
        try{
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $arr_1 = $validator->errors()->keys();
                $arr_2 = $validator->errors()->all();

                foreach ($arr_1 as $key => $value) {
                    $arr[$value] = $arr_2[$key];
                }

                return response()->json(['error'=>$arr]);
            }else{
                $user_id = Auth::user()->id;
                $item = Barcode::find($request->id);
                $item->barcode =  trim($request->barcode);
                $item->name =  $request->name;
                $item->model =  $request->model;
                $item->manufacturer  =  $request->manufacturer;

                $file_old_deleted = $request->file_old_deleted;
                $file_old_deleted = json_decode($file_old_deleted ,true);
                $str_link_base64 = '';
                $image = explode(',', $item->image);

                if (count($image) == 1 && strpos($item->image, "http") !== false) {
                    if (count($file_old_deleted) == 0) {
                        include_once(public_path('libs/PHPCrawler.class.php'));
                        include_once(public_path('libs/simple_html_dom.php'));
                        libxml_use_internal_errors(true);
    
                        $arrContextOptions = array(
                            "ssl" => array(
                                "verify_peer" => false,
                                "verify_peer_name" => false,
                            ),
                        );
    
                        $contentImage = file_get_contents($item->image, false, stream_context_create($arrContextOptions));

                        if ($contentImage !== FALSE) {
                            $ext = 'png';
                            $fileName = round(microtime(true) * 1000). '.' . $ext;
                            $str_link_base64 .= $fileName . ',';
                            $fp2 = fopen(public_path('uploads/barcode/') . $fileName, "w");
                            fwrite($fp2, $contentImage);
                            fclose($fp2);
                        }
                    }
                } else {
                    if (count($file_old_deleted) > 0) {
                        foreach ($image as $key => $value) {
                            if (!in_array($value, $file_old_deleted)) {
                                $str_link_base64 .= $value . ',';
                            } else {
                                if (file_exists(public_path('uploads/barcode/') . $value)) {
                                    unlink(public_path('uploads/barcode/') . $value);
                                }
                            }
                        }
                    } else {
                        $str_link_base64 = ($item->image != '') ? $item->image . ',' : '';
                    }
                }

                if (trim($str_link_base64) == ',') {
                    $str_link_base64 = rtrim($request->str_image, ",");
                } else {
                    $str_link_base64 = ltrim($str_link_base64, ',') . rtrim($request->str_image, ",");
                }

                $item->image = rtrim($str_link_base64, ",");
                $item->avg_price =  str_replace('+','',$request->avg_price);
                $item->currency_unit =  $request->currency_unit;
                $item->spec =  $request->spec;
                $item->feature =  $request->feature;
                $item->description =  $request->description;
                $item->updated_at  =  date('Y-m-d H:i:s');
                $item->user_id = $user_id;
                $item->save();

		        BatvHelper::updateBarcode($item);

                // $checkSendEmail = SettingEmail::where('function', 'barcode.update')->first();

                $index = array_search('barcode.update',array_column($this->settingEmail, 'function'));
                $checkSendEmail = $this->settingEmail[$index];

                if($checkSendEmail->user == 1){
                    $template = 'layouts_frontend.email.to_user.update_barcode_success';
                    $title = 'The product '. trim($request->barcode) .' has been updated.';
                    $email = Auth::user()->email;
                    $content_mail = array(
                        'barcode' =>  trim($request->barcode)
                    );

                    BatvHelper::sendEmail($template, $title, $email, $content_mail);
                }

                if($checkSendEmail->admin == 1){
                    $template = 'layouts_frontend.email.to_admin.update_barcode_success';
                    $title = 'A barcode of '. Auth::user()->name .' have been updated.';
                    $email = env('MAIL_USERNAME', 'nhansu@tohsoft.com');
                    $content_mail = array(
                        'barcode'   =>  trim($request->barcode),
                        'user'      =>  Auth::user()->name
                    );

                    BatvHelper::sendEmail($template, $title, $email, $content_mail);
                }
                
                return response()->json(['success'=>isset($this->messages['barcode.update_susscess']) ? $this->messages['barcode.update_susscess'] : 'The product has been updated.','user_id'=>$user_id]);
            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }

    public function deleteBarCodebyUser(Request $request){
        $id = $request->input('id');
        try {
            if($id){
                $item = BarCode::where('user_id','=',Auth::user()->id)->find($id);
                $item->delete($id);
                $res=array('Response'=>"Success","Message"=>"The product has been deleted" );

                // send email
                // $checkSendEmail = SettingEmail::where('function', 'barcode.delete')->first();
                
                $index = array_search('barcode.delete',array_column($this->settingEmail, 'function'));
                $checkSendEmail = $this->settingEmail[$index];

                if($checkSendEmail->user == 1){
                    $template = 'layouts_frontend.email.to_user.delete_barcode_success';
                    $title = 'The product '. trim($request->barcode) .' has been deleted.';
                    $email = Auth::user()->email;
                    $content_mail = array(
                        'barcode' =>  trim($request->barcode)
                    );

                    BatvHelper::sendEmail($template, $title, $email, $content_mail);
                }

                if($checkSendEmail->admin == 1){
                    $template = 'layouts_frontend.email.to_admin.delete_barcode_success';
                    $title = 'A barcode of '. Auth::user()->name .' have been deleted.';
                    $email = env('MAIL_USERNAME', 'nhansu@tohsoft.com');
                    $content_mail = array(
                        'barcode'   =>  trim($request->barcode),
                        'user'      =>  Auth::user()->name
                    );

                    BatvHelper::sendEmail($template, $title, $email, $content_mail);
                }

                echo json_encode($res);
            }else{
                $res=array('Response'=>"Error","Message"=>"An error occurred during deletion process please try again" );
                echo json_encode($res);
            }
        } catch (Exception $e) {
            $res=array('Response'=>"Error","Message"=>"An error occurred during deletion process please try again" );
            echo json_encode($res);
        }
    }

    public function deleteMultiBarCodebyUser(Request $request){
        if(isset($request) && $request->input('id_list')){
            $id_list = $request->input('id_list');
            $id_list = rtrim($id_list, ',');
            if(Barcode::deleteMultiBarCodebyUser($id_list)){
                // send email
                // $checkSendEmail = SettingEmail::where('function', 'barcode.multiDelete')->first();

                $index = array_search('barcode.multiDelete',array_column($this->settingEmail, 'function'));
                $checkSendEmail = $this->settingEmail[$index];

                if($checkSendEmail->user == 1){
                    $template = 'layouts_frontend.email.to_user.delete_barcodes_success';
                    $title = 'Barcodes have been deleted.';
                    $email = Auth::user()->email;
                    $content_mail = array(
                        'barcode' =>  trim($request->barcode)
                    );

                    BatvHelper::sendEmail($template, $title, $email, $content_mail);
                }

                if($checkSendEmail->admin == 1){
                    $template = 'layouts_frontend.email.to_admin.delete_barcodes_success';
                    $title = 'A lot of barcodes of '. Auth::user()->name .' have been deleted.';
                    $email = env('MAIL_USERNAME', 'nhansu@tohsoft.com');
                    $content_mail = array(
                        'barcode'   =>  trim($request->barcode),
                        'user'      =>  Auth::user()->name
                    );

                    BatvHelper::sendEmail($template, $title, $email, $content_mail);
                }
                $res=array('Response'=>"Success","Message"=>"Barcodes have been deleted" );
            }else{
                $res=array('Response'=>"Error","Message"=>"A barcode does not exist" );
            }
            echo json_encode($res);
        }
    }

    public function deleteBarcodeAjax(Request $request){
        dd($request);
    }

    public function listBarCodeUserAjax(Request $request)
    {
        return Datatables::of(
            Barcode::listBarCodebyRequest($request)
                )->addColumn('action', function ($barcode) {
                    return '
                        <a href="'. url('/') . '/barcode/view/' . $barcode->id.'" class="btn btn-xs btn-default">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                        <a href="'. url('/') . '/barcode/edit/' . $barcode->id.'" class="btn btn-xs btn-default">
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <div id="remove-'.$barcode->id.'" data-id="'.$barcode->id.'" onclick="removeItem('.$barcode->id.')" class="btn btn-xs btn-default">
                            <i class="glyphicon glyphicon-remove"></i>
                        </div>';
                })->addColumn('all', function ($barcode) {
                    return '<input type="checkbox" name="selectCol" id="barcode-'.$barcode->id.'" class="check-barcode" value="'.$barcode->id.'" data-column="'.$barcode->id.'">';
                })->removeColumn('id')->make(true);
    }
}

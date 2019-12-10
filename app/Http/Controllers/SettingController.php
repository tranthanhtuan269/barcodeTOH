<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\SettingPriceBarCode;
use App\Models\SettingBarCode;
use App\Models\SettingBarCodeFree;
use App\Models\Message;
use App\Models\SettingEmail;
use Auth;
use Cache;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Helpers\BatvHelper;

class SettingController extends Controller{

    public function getListSettingPackageBarCode(){
        $data = SettingBarCode::get();
        return view('layouts_backend.setting.package-barcode.index',['data'=>$data]);
    }

    public function settingBarcodeType(){
        $setting = SettingBarCodeFree::find(2);
        if(isset($setting)){
            $setting->number = 1 - $setting->number;
            if($setting->save()){
                return response()->json(['status'=>'200']);
            }
        }
        return response()->json(['status'=>'404']);
    }

    public function getSettingMessage(){
        $data = Message::get();
        return view('layouts_backend.setting.messages',['data'=>$data]);
    }

    public function putSettingMessage(Request $request){
        if(isset($request->id) && $request->id > 0){
            $message = Message::find($request->id);
            if(isset($message)){
                $message->message = $request->message;
                if($message->save()){
                    Cache::forget('messages');
                    return response()->json(["Response"=>"Success","Message"=>"The data has been saved"]);
                }
                return response()->json(["Response"=>"Error","Message"=>"An error occurred during save process, please try again"]);
            }
        }
    }

    public function emailConfig(){
        $data = Message::get();
        return view('layouts_backend.setting.emails',['data'=>$data]);
    }

    public function emailSaveConfig(Request $request){
        if(isset($request->id) && $request->id > 0 && isset($request->obj)){
            $email = SettingEmail::find($request->id);
            if(isset($email)){
                /// option 1
                if($request->obj    ==  'user'){
                    $email->user    =   $request->state;
                }
                if($request->obj    ==  'admin'){
                    $email->admin   =   $request->state;
                }
                if($email->save()){
                    Cache::forget('SettingEmail');
                    return response()->json(["Response"=>"Success","Message"=>"The data has been saved"]);
                }

                /// option 2
                // SettingEmail::where('id', $request->id)->update([$request->obj => $request->state]);

                return response()->json(["Response"=>"Error","Message"=>"An error occurred during save process, please try again"]);
            }
        }
    }

    public function getSettingBarCodeFree(){
        $numberBarCodeFree = SettingBarCodeFree::find(1)->value('number');
        $barCodeStatus = SettingBarCodeFree::where('id', 2)->value('number');
        
        return view('layouts_backend.setting.number-barcode-free.index',[
            'numberBarCodeFree'=>$numberBarCodeFree, 
            'barCodeStatus'=>$barCodeStatus
            ]);
    }

    public function putSettingBarCodeFree(Request $request){
        try{ 
            $rules = [
                'number' => 'required|numeric|min:1',
            ];
            $messages = [];  
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $numberBarCodeFree = SettingBarCodeFree::where('id',1)->update(['number'=> $request->number]);
                return back()->with(['flash_message_succ' => 'Successful!']);
            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }

    public function settingPackageBarCodeAjax( Request $request ){
        
        if( $request->ajax() ){
            $action = $request->type;
            if(!empty($action)) {
                switch($action) {
                    case "add":
                        $item = new SettingBarcode;
                        $item->number =  $request->number;
                        $item->price =  $request->price;
                        $item->save();
                        $insert_id = $item->id;
                        echo '<tr class="message-box text-center"  id="message_' . $insert_id . '">
                            <td class="message-salary">' . $request->number . '</td>
                            <td class="message-welfarefund">' . $request->price . '</td>
                            <td class="button_special">
                                <div class="item_1">
                                    <a class="btnEditAction" name="edit" onClick="showEditBox(this,' . $insert_id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <a class="btnDeleteAction" name="delete" onClick="callCrudAction(\'delete\',' . $insert_id . ')"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;"></i></a>
                                </div>
                                <div class="item_2"></div>

                            </td>
                        </tr>';
                        
                        break;
                        
                    case "edit":
                        $item = SettingBarcode::find($request->id);
                        $item->number =  $request->number;
                        $item->price =  $request->price;
                        $item->save();
                        echo ' <td class="message-salary">' . $request->number . '</td>
                                <td class="message-welfarefund">' . $request->price . '</td>
                                <td class="button_special">
                                    <div class="item_1">
                                        <a class="btnEditAction" name="edit" onClick="showEditBox(this,' .$request->id . ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        <a class="btnDeleteAction" name="delete" onClick="callCrudAction(\'delete\',' . $request->id. ')"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;"></i></a>
                                    </div>
                                    <div class="item_2"></div>
                                </td>';
                        break;          
                    
                    case "delete": 
                        SettingBarcode::destroy($request->id);
                        break;
                }
            }
        }
    }



    public function getListPriceBarCode(){
        $data = SettingPriceBarCode::getListPriceBarCode();
        return view('layouts.frontend.setting.pricebarcode.index',['data'=>$data]);
    }

    public function getPriceBarCodeAdd(){
        return view('layouts.frontend.setting.pricebarcode.add');
    }

    public function postPriceBarCodeAdd(Request $request){
        try{
            // Phần Validation xử lý trường hợp chờm khoảng thời gián
            Validator::extend('check_setting_price_barcode', function($attribute, $value, $parameters, $validator) {
                $data = $validator->getData();
                $apply_from = BatvHelper::formatDate($data['apply_from'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $apply_to = BatvHelper::formatDate($data['apply_to'],'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $check = SettingPriceBarCode::checkSettingPriceBarCode( '',$apply_from,$apply_to );
                return $check>0?FALSE:TRUE;
            }); 
            $rules = [
                'title' => 'required',
                'value' => 'required|numeric|min:1',
                'apply_from' => 'required',
                'apply_to' => 'required',
                'apply_to' => 'validator_datetime_from_to:apply_from|check_setting_price_barcode',
            ];
            $messages = [
                'title.required'=>'Tiêu đề không được để trống',
                'value.required' => 'Giá trị không được để trống',
                'value.numeric' => 'Giá trị phải là số',
                'value.min' => 'Giá trị phải là số nguyên dương',
                'apply_from.required' => 'Thời gian hiệu lực không được để trống',
                'apply_to.required' => 'Thời gian hết hiệu lực không được để trống',
                'apply_to.validator_datetime_from_to'=>'Chọn thời gian hiệu lực không hợp lệ',
                'apply_to.check_setting_price_barcode'=>'Thời gian chọn nằm trong khoảng thời gian trước đó',
            ];  
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $item = new SettingPriceBarCode;
                $item->title =  $request->title;
                $item->value =  $request->value;
                $item->apply_from =  BatvHelper::formatDate($request->apply_from,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $item->apply_to =  BatvHelper::formatDate($request->apply_to,'d/m/Y',$formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
                $item->created_by =  Auth::user()->id;
                $item->created_at = date('Y-m-d H:i:s');
                $item->save();
                return back()->with(['flash_message_succ' => 'Successful!']);
            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }
    
    public function getPriceBarCodeDel($id){
        $item = SettingPriceBarCode::find($id);
        $item->delete($id);
        return back()->with(['flash_message_succ' =>'Delete Successfully']);
    }

    public function getSettingPriceBarCode()
    {
        $data = SettingBarCode::find(1);
        return view('layouts_backend.setting.price-barcode.index', ['data' => $data]);
    }


    public function setSettingPriceBarCode(Request $request)
    {
        if(is_numeric($request->price)){
            $numberBarCodeFree = SettingBarCode::where('id',1)->update(['price'=> $request->price]);
            return back()->with(['flash_message_succ' =>'Successful!']);
        }else{
            return back()->with(['flash_message_error' =>'Update Price must be a number']);
        }
    }
}

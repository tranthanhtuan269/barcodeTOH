<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use File;
use App\Helpers\BatvHelper;
//use App\Mylibs\Myfunction;
use App\Mylibs\ResizeImage;
class TaikhoanController extends Controller
{
    private $messages;

    public function __construct()
    {
        $this->messages = \DB::table('messages')->where('category', 2)->pluck('message', 'name');
    }

    /*---------BACK-END---------*/
     public function getTaikhoanInfo($id){
        $data = User::findOrFail($id);
        return view('layouts_backend.users.info_taikhoan',['data'=>$data]);
    }


     public function getTaikhoanEditPass($id){
        $data = User::findOrFail($id);
        return view('layouts_backend.users.editpass_taikhoan',['data'=>$data]);
    }

    public function putTaikhoanEditPass(Request $request,$id){
        try{
            $rules = [
                'passwordCurrent' =>'required',
                'inputPassword'=>'required|min:8|max:32|different:passwordCurrent',
                'inputPassword_confirmation'=>'required|min:8|max:32|same:inputPassword',
            ];
            $messages = [
                'passwordCurrent.required'=>'The current password field is required',

                'inputPassword.required'=>'The password field is required',
                'inputPassword.min'=> 'The password must be at least 8 characters',
                'inputPassword.max'=>'The password must not be greater than 32 characters',
                'inputPassword.different'=>'The password and the confirm password should not be same as the current password',

                'inputPassword_confirmation.required'=>'The comfirm password field is required',
                'inputPassword_confirmation.min'=> 'The confirm password must be at least 8 characters',
                'inputPassword_confirmation.max'=>'The confirm password must not be greater than 32 characters',
                'inputPassword_confirmation.same'=>'The password does not match the confirm password',

            ];
            $pass = $request->input('passwordCurrent');
            $user = User::find($id);
            $validator = Validator::make($request->all(),$rules,$messages);
            $validator->after(function($validator) use ($pass,$user)  {
                if (!Hash::check($pass,$user->password)){
                    $validator->errors()->add('field', 'The current password incorrect!');
                }
            });
            if ($validator->fails()) {
                //validator false
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                 $user->password = bcrypt($request->input('inputPassword'));
                 $user->save();
                 return redirect()->route('getTaikhoanInfo',['id'=>$id])->with(['flash_message_succ' => 'Your password has been changed!']);

            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }
    public function getTaikhoanEditInfo($id){
    	$data = User::findOrFail($id);
        return view('layouts_backend.users.editinfo_taikhoan',['data'=>$data]);
    }
    public function putTaikhoanEditInfo(Request $request,$id){

        try{    
            $rules = [
                'inputName' =>'required|min:3'
            ];
            $messages = [
                'inputName.required'=>'The name field is required',
                'inputName.min' => 'The name must be at least 3 characters'
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            $user = User::findOrFail($id);
            if($user){
                $user->name         = $request->inputName;
                $user->avatar       = $request->avatar;
                $user->save();
                return redirect()->route('getTaikhoanInfo',['id'=>$id])->with(['flash_message_succ' => 'Your account has been updated']);
            }
            return redirect()->back()->with(['flash_message_err'=>'An error occurred during upload process, please try again']);
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }


    /*-------------- FRONT-END ------------*/
    public function getInfoAccount($id){
        if( Auth::user()->id == $id ){
            $data = User::findOrFail($id);
            return view('layouts_frontend.account.info',['info_account'=>$data]);
        }else{
            return redirect()->route('getInfoAccount', ['id' => Auth::user()->id]);
        }
    }

    public function getAccountEdit($id){
        if( Auth::user()->id == $id ){
            $data = User::findOrFail($id);
            return view('layouts_frontend.account.edit',['info_account'=>$data]);
        }else{
            return redirect()->route('getAccountEdit', ['id' => Auth::user()->id]);
        }
    }

    public function putAccountEdit(Request $request,$id){
        try{
            $birthday = (!empty($request->birthday) )? BatvHelper::formatDateStandard('d/m/Y',$request->birthday,'Y-m-d'):'';
            $rules = [
                'name' =>'required|min:3|max:50',
                'fileImage' => 'image|mimes:jpeg,jpg,png,gif|max:5000',
                'birthday' => 'date_format:"d/m/Y"|regex:/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/|validate_birthday:'.$birthday,
                'phone' =>'max:20|not_regex:"/^[\+]?[(]?[0-9]{1,3}[)]?[-\s]?[0-9]{1,3}[-\s]?[0-9]{4,9}$/"',
                'address' =>'max:255',
                'career' =>'max:255',
                'organization' =>'max:255',
            ];
            $messages = [
                            'name.required'=> isset($this->messages['user.firstname.required']) ? $this->messages['user.firstname.required'] : 'The name field is required.',
                            'name.min'=> isset($this->messages['user.firstname.min']) ? $this->messages['user.firstname.min'] : 'The name must be at least 3.',
                            'name.max'=> isset($this->messages['user.firstname.max']) ? $this->messages['user.firstname.max'] : 'The name may not be greater than 50 characters.',
                            'fileImage.image'=> isset($this->messages['user.fileImage.image']) ? $this->messages['user.fileImage.image'] : 'The image must be an image.',
                            'fileImage.mimes'=> isset($this->messages['user.fileImage.mimes']) ? $this->messages['user.fileImage.mimes'] : 'The image must be a file of type: jpeg,jpg,png,gif.',
                            'fileImage.max'=> isset($this->messages['user.fileImage.max']) ? $this->messages['user.fileImage.max'] : 'The image may not be greater than 5000 kilobytes.',
                            'birthday.date_format'=> isset($this->messages['user.birthday.date_format']) ? $this->messages['user.birthday.date_format'] : 'The birthday does not match the format d/m/Y.',
                            'birthday.validate_birthday'=> isset($this->messages['user.birthday.validate_birthday']) ? $this->messages['user.birthday.validate_birthday'] : 'The birthday does not be greater than current date.',
                            'phone.max'=> isset($this->messages['user.phone.max']) ? $this->messages['user.phone.max'] : 'The phone may not be greater than 20 characters.',
                            'phone.not_regex'=> isset($this->messages['user.phone.not_regex']) ? $this->messages['user.phone.not_regex'] : 'The phone must be a valid phone number.',
                            'address.max'=> isset($this->messages['user.address.max']) ? $this->messages['user.address.max'] : 'The address may not be greater than 255 characters.',
                            'career.max'=> isset($this->messages['user.career.max']) ? $this->messages['user.career.max'] : 'The career may not be greater than 255 characters.',
                            'organization.max'=> isset($this->messages['user.organization.max']) ? $this->messages['user.organization.max'] : 'The organization may not be greater than 255 characters.',
                        ];
            $validator = Validator::make($request->all(),$rules,$messages);
            $user = User::findOrFail($id);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                $user->name         = $request->name;
                $user->avatar       = $request->avatar;
                $user->gender       = $request->gender;
                $user->birthday     = $birthday;
                $user->phone        = $request->phone;
                $user->address      = $request->address;
                $user->career       = $request->career;
                $user->organization = $request->organization;
                $user->updated_by   = Auth::user()->id;
                $user->updated_at   = date('Y-m-d H:i:s');
                $user->save();
                return redirect()->route('getInfoAccount',['id'=>$id])->with(['flash_message_succ' => isset($this->messages['user.edit_account_success']) ? $this->messages['user.edit_account_success'] : 'Your account has been updated!']);
            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }
}

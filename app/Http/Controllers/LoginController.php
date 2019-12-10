<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use Auth;
use Illuminate\Support\MessageBag;
class LoginController extends Controller
{
    //
    public function __construct(){
    	
    }
    public function getLogin(){
    	if(Auth::check()){
	        return redirect('admin/user/list');
	    }else{
            return view('layouts_backend.login');
        }
    	
    }
    public function postLogin(Request $request){
    	//dd($request->all());
    	$rules = [
    		'email'=>'required|email',
    		'password'=>'required|min:6'
    	];

    	$messages = [
    		// 'email.required'=> 'Bạn chưa nhập email',
    		// 'email.email'=>'Email không đúng định dạng',
    		// 'password.required'=> 'Bạn chưa nhập mật khẩu',
    		// 'password.min'=>'Mật khẩu phải có ít nhất 6 ký tự',

    	];

    	$validator = Validator::make($request->all(),$rules,$messages);
        if ($validator->fails()) {
        	// Validator fail
        	//return redirect()->back()->withErrors($validator);
        	return redirect()->back()->withErrors($validator)->withInput();
        }else{
        	$email = $request->input('email');
        	$password = $request->input('password');

        	if(Auth::attempt(['email'=>$email,'password'=>$password])){
        		//return view('dashboard',['user'=>$data]);
                return redirect()->intended('admin/user/list');

        	}else{
        		$errors = new MessageBag(['errorLogin'=>'The email or password is incorrect']);
        		return redirect()->back()->withErrors($errors);
       		}
        }
    }
    public function getLogout(){
        Auth::logout();
        return redirect()->route('login');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,File,Auth;
use App\Http\Requests;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Log;
use App\Models\Page;
use App\Models\Personnel;
use App\Models\BarCode;
use App\Models\Message;
use App\Models\SettingEmail;
use Illuminate\Support\Facades\Hash;
use App\Helpers\BatvHelper;
use App\Models\SettingBarCodeFree;
use Cache;


class PublicController extends Controller
{
	private $messages;
	private $settingEmail;

    public function __construct()
    {
        $this->messages = Cache::remember('messages', 1440, function() {
            return \DB::table('messages')->where('category', 2)->pluck('message', 'name');
        });

        $this->settingEmail = Cache::remember('SettingEmail', 1440, function() {
            return \DB::table('setting_email')->get();
        });
	}
	
    public function getSearchBarcode(Request $request, $slug){
    	$barcode = explode("-", $slug);
    	$barcode = $barcode[count($barcode) - 1];
		$data = BarCode::findBarCodebyBarCode($barcode);
		return view('layouts_frontend.barcode.search',['data'=>$data, 'barcode' => $barcode]);
    }

    public function getIndex(Request $request){
    	$timeCreated = date('Y-m-d');
    	if( isset($_GET['barcode']) ){
	        try{
	        	$trimBarcode = trim($request->barcode);
	       		$data = BarCode::findBarCodebyBarCode($trimBarcode);
		        if( !isset($data) ){
		        	// data == null
		            $json = file_get_contents('http://barcode.tohapp.com/barcode_api.php?barcode='.$trimBarcode);
		            $data = json_decode($json);

		            if( !empty($data->barcode) ){
		            	// data != null
		            	$avg_price = $currency_unit = '';
		            	if( !empty($data->avg_price) ){
		            		$arr_avg_price = (explode(" ",$data->avg_price));
		            		$avg_price = $arr_avg_price[0];
		            		$currency_unit = $arr_avg_price[1];
		            		if ($currency_unit == "") {
		            			$currency_unit = "USD";
		            		}
		            	}
		            	$fileName = '';
		            	if( !(empty($data->image)) ){
							$fileContent = file_get_contents($data->image);
							$ext = pathinfo($data->image, PATHINFO_EXTENSION);
							$fileName = time().'.'.$ext;
							File::put(public_path('uploads/barcode/') . $fileName, $fileContent);
		            	}

		                $item 					= new BarCode;
		                $item->barcode 			= $data->barcode;
		                $item->name 			= $data->name;
		                $item->model 			= $data->model;
		                $item->manufacturer  	= $data->manufacturer;
		                $item->image 			= $fileName;
		                $item->avg_price 		= $avg_price;
		                $item->currency_unit 	= $currency_unit;
		                $item->spec 			= $data->spec;
		                $item->feature 			= $data->feature;
		                $item->description 		= $data->description;
		                $item->insert_time  	= date('Y-m-d H:i:s');
		                $item->user_id 			= 1;
		                $item->save();


		        		// save log
		        		$log 					= new Log;
		        		$log->barcode 			= $trimBarcode;
		        		$log->user_id 			= -1;
		        		$log->found_on_web 		= 0;
		        		$log->found_by_api 		= 1;
		        		$log->not_found 		= 0;
		        		$log->created_at		= $timeCreated;
		        		$log->updated_at		= $timeCreated;
		        		$log->save();
		            }else{
		            	// data not found on api
		            	// save log
		            	$logCkeck = Log::where('barcode', $trimBarcode)->where('created_at', $timeCreated)->first();
			        	if($logCkeck){
			        		// exist
			        		$logCkeck->not_found 	+= 1;
			        		$logCkeck->save();
			        	}else{
			        		$log 					= new Log;
			        		$log->barcode 			= $trimBarcode;
			        		$log->user_id 			= -1;
			        		$log->found_on_web 		= 0;
			        		$log->found_by_api 		= 0;
			        		$log->not_found 		= 1;
			        		$log->created_at		= $timeCreated;
		        			$log->updated_at		= $timeCreated;
			        		$log->save();
			        	}
		            }
		        }else{
		        	// data != null => save log
		        	$logCkeck = Log::where('barcode', $trimBarcode)->where('created_at', $timeCreated)->first();
		        	if($logCkeck){
		        		// exist
		        		$logCkeck->found_on_web += 1;
		        		$logCkeck->save();
		        	}else{
		        		// not exist
		        		$log 					= new Log;
		        		$log->barcode 			= $trimBarcode;
		        		$log->user_id 			= $data->user_id;
		        		$log->found_on_web 		= 1;
		        		$log->found_by_api 		= 0;
		        		$log->not_found 		= 0;
			        	$log->created_at		= $timeCreated;
		        		$log->updated_at		= $timeCreated;
		        		$log->save();
		        	}
				}
				
				if ($data->barcode == '') {
					return view('layouts_frontend.barcode.search',['data'=>$data]);
				} else {
					return redirect()->route('seo-barcode', ['slug' => str_slug($data->name, "-"), 'id' => $trimBarcode]);
				}
				
	        } catch (\Illuminate\Database\QueryException $ex){
	            return $ex->getMessage(); 
	        }
    	}else{
    		return view('layouts_frontend.dashboard');
    	}
    }

    public function registerAjax( Request $request ){
        if ($request->ajax()) {
	        try{
	            $rules = [
	                'firstname' =>'required|min:3|max:50',
	                'email' =>'required|unique:users,email|not_regex:"/^[_a-zA-Z0-9-]{2,}+(\.[_a-zA-Z0-9-]{2,}+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/"',
		            'password'=>'required|min:8|max:32',
		            'confirmpassword'=>'required|same:password',
		            'captcha'=>'required|recaptcha',
	            ];
	            $messages = [
                    'firstname.required'=> isset($this->messages['user.firstname.required']) ? $this->messages['user.firstname.required'] : 'The name field is required.',
                    'firstname.min'=> isset($this->messages['user.firstname.min']) ? $this->messages['user.firstname.min'] : 'The name must be at least 3.',
                    'firstname.max'=> isset($this->messages['user.firstname.max']) ? $this->messages['user.firstname.max'] : 'The name may not be greater than 50 characters.',
                    'email.required'=> isset($this->messages['user.email.required']) ? $this->messages['user.email.required'] : 'The email field is required.',
            		'email.unique'=> isset($this->messages['user.email.unique']) ? $this->messages['user.email.unique'] : 'The email has already been taken.',
                    'email.not_regex'=> isset($this->messages['user.email.email']) ? $this->messages['user.email.email'] : 'The email must be a valid email address.',
            		'password.required'=> isset($this->messages['user.password.required']) ? $this->messages['user.password.required'] : 'The password field is required.',
            		'password.min'=> isset($this->messages['user.password.min']) ? $this->messages['user.password.min'] : 'The password must be at least 8.',
            		'password.max'=> isset($this->messages['user.password.max']) ? $this->messages['user.password.max'] : 'The password may not be greater than 32 characters.',
    				'confirmpassword.required'=>isset($this->messages['user.confirmpassword.required']) ? $this->messages['user.confirmpassword.required'] :'The confirm password field is required.',
    				'confirmpassword.same'=>isset($this->messages['user.confirmpassword.same']) ? $this->messages['user.confirmpassword.same'] :'Password does not match the confirm password.',
            		'captcha.required'=> isset($this->messages['user.captcha.required']) ? $this->messages['user.captcha.required'] : 'The captcha field is required.',
            		'captcha.recaptcha'=> isset($this->messages['user.captcha.recaptcha']) ? $this->messages['user.captcha.recaptcha'] : 'The registration form is not for robots.',
	            ];
	            $validator = Validator::make(Input::all(), $rules, $messages);
	            if ($validator->fails()) {
	                $errors = [];
	                foreach ($validator->errors()->toArray() as $key => $value) {
	                	foreach ($value as $k => $v) {
	                		$errors[] = $v;
	                	}
	                }
	                $res=array('Response'=>"Error","Message"=>$errors );
	            }else{
			        $user = new User();
			        $user->name = $request->firstname; 
			        $user->email = $request->email; 
			        $user->password = bcrypt(trim($request->password)); 
			        $user->role_id = 2; 
			        $user->created_at = date('Y-m-d H:i:s');
			        $user->status = 1; 
			        $user->number_barcode = SettingBarCodeFree::where('id',1)->value('number'); // insert số lượng barcode được tạo Free
			        $user->save();
			        Auth::login($user, true);

			        // register successfull
			        // send email
	                $index = array_search('user.register',array_column($this->settingEmail, 'function'));
                    $checkSendEmail = $this->settingEmail[$index];

	                if($checkSendEmail->user == 1){
	                    $template = 'layouts_frontend.email.to_user.create_user_success';
	                    $title = 'Confirm registration';
	                    $email = Auth::user()->email;
	                    $content_mail = array(
	                        'email'      =>  $request->email,
	                        'password'      =>  $request->password
	                    );

	                    BatvHelper::sendEmail($template, $title, $email, $content_mail);
	                }

	                if($checkSendEmail->admin == 1){
	                    $template = 'layouts_frontend.email.to_admin.create_user_success';
	                    $title = 'A user has been registered.';
	                    $email = env('MAIL_USERNAME', 'nhansu@tohsoft.com');
	                    $content_mail = array(
	                        'email'      =>  $request->email
	                    );

	                    BatvHelper::sendEmail($template, $title, $email, $content_mail);
	                }
	                // end send email

			        $res=array('Response'=>"Success","Message"=>isset($this->messages['user.register_success']) ? $this->messages['user.register_success'] : 'You have been successfully registered.' );
	            }
	            echo json_encode($res);
	        } catch (\Illuminate\Database\QueryException $ex){
	            return $ex->getMessage(); 
	        }
        }
    }

    public function loginAjax( Request $request ){
        if ($request->ajax()) {
	        try{
		    	$rules = [
		    		'email'=>'required|email',
		    		'password'=>'required'
		    	];

                $messages = [
                    'email.required'=> isset($this->messages['user.email.required']) ? $this->messages['user.email.required'] : 'The email field is required.',
                    'email.email'=> isset($this->messages['user.email.email']) ? $this->messages['user.email.email'] : 'The email must be a valid email address.',
                    'password.required'=> isset($this->messages['user.password.required']) ? $this->messages['user.password.required'] : 'The password field is required.',
                ];


		    	$validator = Validator::make(Input::all(), $rules, $messages);
		        if ($validator->fails()) {
	                $errors = [];
	                foreach ($validator->errors()->toArray() as $key => $value) {
	                	foreach ($value as $k => $v) {
	                		$errors[] = $v;
	                	}
	                }
	                $res=array('Response'=>"Error","Message"=>$errors );
		        }else{
		        	$email = $request->email;
		        	$password = $request->password;
		        	$remember = ( $request->remember == "true" ) ? true : false ;
		        	if(Auth::attempt(['email'=>$email,'password'=>$password, 'status' => 1], $remember)){
				        if(isset($messages['user.login_success'])){
				        	$res=array('Response'=>"Success","Message"=>$messages['user.login_success'] );
				        }else{
		                	$res=array('Response'=>"Success","Message"=>"You are logined" );
				        }
		        	}else{
				        $res=array('Response'=>"Error","Message"=>isset($this->messages['user.login_error']) ? $this->messages['user.login_error'] : 'Email or Password incorrect.' );
		       		}
		        }
	            echo json_encode($res);
	        } catch (\Illuminate\Database\QueryException $ex){
	            return $ex->getMessage(); 
	        }
        }
    }

    public function getLogout(){
        Auth::logout();
        return redirect('/');
    }

    public function changepassAjax( Request $request ){
        if ($request->ajax()) {
	        try{
	        	$id = Auth::user()->id;
		    	$rules = [
		            'password_old'=>'required',
		            'password'=>'required|min:8|max:32|different:password_old',
		            'confirmpassword'=>'required|same:password',
		    	];

		    	$messages = [
                    		'password_old.required'=> isset($this->messages['user.password_old.required']) ? $this->messages['user.password_old.required'] : 'The password old field is required.',
                    		'password.required'=> isset($this->messages['user.password.required']) ? $this->messages['user.password.required'] : 'The password field is required.',
                    		'password.min'=> isset($this->messages['user.password.min']) ? $this->messages['user.password.min'] : 'The password must be at least 8.',
                    		'password.max'=> isset($this->messages['user.password.max']) ? $this->messages['user.password.max'] : 'The password may not be greater than 32 characters.',

                    		'password.different'=> isset($this->messages['user.password.different']) ? $this->messages['user.password.different'] : 'The password and the confirm password should not be same as the current password.',
		    				'confirmpassword.required'=>isset($this->messages['user.confirmpassword.required']) ? $this->messages['user.confirmpassword.required'] :'The confirm password field is required.',
		    				'confirmpassword.same'=>isset($this->messages['user.confirmpassword.same']) ? $this->messages['user.confirmpassword.same'] :'Password does not match the confirm password.',
		    			];

		    	$user = User::find($id);
		    	$validator = Validator::make(Input::all(), $rules, $messages);
			    if (!Hash::check($request->password_old,$user->password)){
			        $res=array('Response'=>"Error","Message"=>['password_old'=>isset($this->messages['user.password_confirm_current']) ? $this->messages['user.password_confirm_current'] :'Current passwords does not exactly!.'] );
			    }else{
			        if ($validator->fails()) {
		                $errors = [];
		                foreach ($validator->errors()->toArray() as $key => $value) {
		                	foreach ($value as $k => $v) {
		                		$errors[] = $v;
		                	}
		                }
		                $res=array('Response'=>"Error","Message"=>$errors );
			        }else{
						$item = User::find($id);
						$item->password = bcrypt($request->password);
						$item->save();

						$res=array('Response'=>"Success","Message"=>isset($this->messages['user.change_pass_sussess']) ? $this->messages['user.change_pass_sussess'] :'You have changed the password to success' );
			        }
			    }

	            echo json_encode($res);
	        } catch (\Illuminate\Database\QueryException $ex){
	            return $ex->getMessage(); 
	        }
        }
    }

    public function resetCodeAjax( Request $request ){
        if ($request->ajax()) {
	        try{
	            $rules = [
	                'email' =>'required|not_regex:"/^[_a-zA-Z0-9-]{2,}+(\.[_a-zA-Z0-9-]{2,}+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/"',
	            ];
                $messages = [
                    'email.required'=> isset($this->messages['user.email.required']) ? $this->messages['user.email.required'] : 'The email field is required.',
                    'email.not_regex'=> isset($this->messages['user.email.email']) ? $this->messages['user.email.email'] : 'The email must be a valid email address.',
                ];
	            $validator = Validator::make(Input::all(), $rules, $messages);
	            if ($validator->fails()) {
	                $errors = [];
	                foreach ($validator->errors()->toArray() as $key => $value) {
	                	foreach ($value as $k => $v) {
	                		$errors[] = $v;
	                	}
	                }
	                $res=array('Response'=>"Error","Message"=>$errors );
	            }else{
	            	$email = $request->email;
	            	$reset_code = BatvHelper::CreateKey();
	            	$id = User::where('email',trim($email))->value('id');
	            	if( $id ){
				        $item = User::find($id);
				        $item->reset_code = $reset_code; 
				        $item->reset_code_time = date('Y-m-d H:i:s');
				        $item->save();

		            	// Gửi mail
			            $content_mail = array(
			                                'reset_code' =>  $reset_code,
			                                'link'		 => route('resetpass').'?email='.$email.'&reset_code='.$reset_code,
			                            );

			            \Mail::send('layouts_frontend.email.account_reset_code', $content_mail, function($message) use ($email) {
			                $message->from(env('MAIL_USERNAME', 'nhansu@tohsoft.com'), 'TOH');
			                $title = "[Barcode] Reset code for change password";
			                $message->to($email)->subject($title);
			            });

				        $res=array('Response'=>"Success","Message"=>isset($this->messages['user.send_message_success']) ? $this->messages['user.send_message_success'] : 'We’ve sent reset code to your email.' );
	            	}else{
				        $res=array('Response'=>"Error","Message"=>isset($this->messages['user.send_message_error']) ? $this->messages['user.send_message_error'] : 'Sorry, input email is not a valid account.' );
	            	}

	            }

	            echo json_encode($res);
	        } catch (\Illuminate\Database\QueryException $ex){
	            return $ex->getMessage(); 
	        }
        }
    }


    public function resetpass(){
    	Auth::logout();
		return view('layouts_frontend.account.index');
    }

    public function resetpassAjax( Request $request ){
        if ($request->ajax()) {
	        try{
	        	$email = trim($request->email);
	        	$reset_code = trim($request->reset_code);
	        	$password = trim($request->password);
	        	$confirmpassword = trim($request->confirmpassword);
	        	$check = User::checkAccount($reset_code,$email);
	        	if( $check ){
		        	$id = User::where('email',$email)->value('id');
			    	$rules = [
			            'password'=>'required|min:8|max:32',
			            'confirmpassword'=>'required|same:password',
			    	];
			    	$messages = [
	                    		'password.required'=> isset($this->messages['user.password.required']) ? $this->messages['user.password.required'] : 'The password field is required.',
	                    		'password.min'=> isset($this->messages['user.password.min']) ? $this->messages['user.password.min'] : 'The password must be at least 8.',
	                    		'password.max'=> isset($this->messages['user.password.max']) ? $this->messages['user.password.max'] : 'The password may not be greater than 32 characters.',
			    				'confirmpassword.required'=>isset($this->messages['user.confirmpassword.required']) ? $this->messages['user.confirmpassword.required'] :'The confirm password field is required.',
			    				'confirmpassword.same'=>isset($this->messages['user.confirmpassword.same']) ? $this->messages['user.confirmpassword.same'] :'Password does not match the confirm password.',
			    			];
			    	$validator = Validator::make(Input::all(), $rules, $messages);
			        if ($validator->fails()) {
	                	$errors = [];
		                foreach ($validator->errors()->toArray() as $key => $value) {
		                	foreach ($value as $k => $v) {
		                		$errors[] = $v;
		                	}
		                }
		                $res=array('Response'=>"Error","Message"=>$errors );
			        }else{
						$item = User::find($id);
						$item->password = bcrypt($password);
						$item->save();
				        $res=array('Response'=>"Success","Message"=>isset($this->messages['user.change_pass_success']) ? $this->messages['user.change_pass_success'] : 'You have changed the password to success.' );
			        }

	        	}else{
			        $res=array('Response'=>"Error","Message"=>isset($this->messages['user.change_pass_error']) ? $this->messages['user.change_pass_error'] : 'Sorry, Email or Code is incorrect.' );
	        	}
	            echo json_encode($res);
	        } catch (\Illuminate\Database\QueryException $ex){
	            return $ex->getMessage(); 
	        }
        }
    }

    // public function uploadImageBarcode(Request $request){
    //     $img_file = '';
    //     if ($request->hasFile('cropped_image')) {
    //         $file_img = $request->file('cropped_image')[0];
    //         $filename = $file_img->getClientOriginalName();
    //         $extension = $file_img->getClientOriginalExtension();
    //         $img_file = time() . $filename .'.png';
    //         $destinationPath = base_path('public/uploads/barcode/');
    //         $file_img->move($destinationPath, $img_file);
    //         return \Response::json(array('code' => '200', 'message' => 'success', 'image_url' => $img_file));
    //     }
    //     return \Response::json(array('code' => '404', 'message' => 'unsuccess', 'image_url' => ""));
    // }

    public function uploadImageBarcode(Request $request){
        $img_file = '';
        if (isset($request->base64)) {
            $data = $request->base64;

			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			$filename = time() . '.png';
			file_put_contents(base_path('public/uploads/barcode/') . $filename, $data);

            return \Response::json(array('code' => '200', 'message' => 'success', 'image_url' => $filename));
        }
        return \Response::json(array('code' => '404', 'message' => 'unsuccess', 'image_url' => ""));
    }

    public function uploadImage(Request $request){
        $img_file = '';
        if (isset($request->base64)) {
            $data = $request->base64;

			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			$filename = time() . '.png';
			file_put_contents(base_path('public/uploads/users/') . $filename, $data);

            return \Response::json(array('code' => '200', 'message' => 'success', 'image_url' => $filename));
        }
        return \Response::json(array('code' => '404', 'message' => 'unsuccess', 'image_url' => ""));
    }

    public function getSlugAjax(Request $request){
        $timeCreated = date('Y-m-d');

    	$trimBarcode = trim($request->barcode);
   		$data = BarCode::findBarCodebyBarCode($trimBarcode);
        if( !isset($data) ){
        	// data == null
            $json = file_get_contents('http://barcode.tohapp.com/barcode_api.php?barcode='.$trimBarcode);
            $data = json_decode($json);

            if( !empty($data->barcode) ){
            	// data != null
            	$avg_price = $currency_unit = '';
            	if( !empty($data->avg_price) ){
            		$arr_avg_price = (explode(" ",$data->avg_price));
            		$avg_price = $arr_avg_price[0];
            		$currency_unit = $arr_avg_price[1];
            		if ($currency_unit == "") {
            			$currency_unit = "USD";
            		}
            	}
            	$fileName = '';
            	if( !(empty($data->image)) ){
					$fileContent = file_get_contents($data->image);
					$ext = pathinfo($data->image, PATHINFO_EXTENSION);
					$fileName = time().'.'.$ext;
					File::put(public_path('uploads/barcode/') . $fileName, $fileContent);
            	}

                $item 					= new BarCode;
                $item->barcode 			= $data->barcode;
                $item->name 			= $data->name;
                $item->model 			= $data->model;
                $item->manufacturer  	= $data->manufacturer;
                $item->image 			= $fileName;
                $item->avg_price 		= $avg_price;
                $item->currency_unit 	= $currency_unit;
                $item->spec 			= $data->spec;
                $item->feature 			= $data->feature;
                $item->description 		= $data->description;
                $item->insert_time  	= date('Y-m-d H:i:s');
                $item->user_id 			= 1;
                $item->save();


        		// save log
        		$log 					= new Log;
        		$log->barcode 			= $trimBarcode;
        		$log->user_id 			= -1;
        		$log->found_on_web 		= 0;
        		$log->found_by_api 		= 1;
        		$log->not_found 		= 0;
        		$log->created_at		= $timeCreated;
        		$log->updated_at		= $timeCreated;
        		$log->save();
            }else{
            	// data not found on api
            	// save log
            	$logCkeck = Log::where('barcode', $trimBarcode)->where('created_at', $timeCreated)->first();
	        	if($logCkeck){
	        		// exist
	        		$logCkeck->not_found 	+= 1;
	        		$logCkeck->save();
	        	}else{
	        		$log 					= new Log;
	        		$log->barcode 			= $trimBarcode;
	        		$log->user_id 			= -1;
	        		$log->found_on_web 		= 0;
	        		$log->found_by_api 		= 0;
	        		$log->not_found 		= 1;
	        		$log->created_at		= $timeCreated;
        			$log->updated_at		= $timeCreated;
	        		$log->save();
	        	}
            }
        }else{
        	// data != null => save log
        	$logCkeck = Log::where('barcode', $trimBarcode)->where('created_at', $timeCreated)->first();
        	if($logCkeck){
        		// exist
        		$logCkeck->found_on_web += 1;
        		$logCkeck->save();
        	}else{
        		// not exist
        		$log 					= new Log;
        		$log->barcode 			= $trimBarcode;
        		$log->user_id 			= $data->user_id;
        		$log->found_on_web 		= 1;
        		$log->found_by_api 		= 0;
        		$log->not_found 		= 0;
	        	$log->created_at		= $timeCreated;
        		$log->updated_at		= $timeCreated;
        		$log->save();
        	}
		}
		
		if ($data->barcode == '') {
			return response()->json(['status' => 404]);
		} else {
			return response()->json(['status' => 200, 'slug' => str_slug($data->name, "-")]);
		}
				
        
    }
}

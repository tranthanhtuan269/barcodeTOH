<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth,Validator,Session,Redirect,Input,URL;
use App\Models\Contact;
use App\Helpers\BatvHelper;
use App\Models\Page;
use App\ContactUs;


class ContactController extends Controller
{
    private $messages;

    public function __construct()
    {
        $this->messages = \DB::table('messages')->where('category', 2)->pluck('message', 'name');
    }

    public function getContact() {
        $data = ContactUs::find(1);
        return view('layouts_frontend.contact.index', ['data'=> $data]);
    }

    public function postContact(Request $request) {
        try{
            $rules = [
                'name' =>'required|max:200',
                'phone' =>'max:20|not_regex:"/^[\+]?[(]?[0-9]{1,3}[)]?[-\s]?[0-9]{1,3}[-\s]?[0-9]{4,9}$/"',
                'email' =>'required|not_regex:"/^[_a-zA-Z0-9-]{2,}+(\.[_a-zA-Z0-9-]{2,}+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/"',
                'question' => 'required',
                'g-recaptcha-response'=>'required|recaptcha',
            ];
            $messages = [
                'name.required'=> isset($this->messages['user.firstname.required']) ? $this->messages['user.firstname.required'] : 'The name field is required.',
                'name.max'=> isset($this->messages['user.firstname.max']) ? $this->messages['user.firstname.max'] : 'The name may not be greater than 50 characters.',
                'phone.required'=> isset($this->messages['user.phone.required']) ? $this->messages['user.phone.required'] : 'The phone field is required.',
                'phone.max'=> isset($this->messages['user.phone.max']) ? $this->messages['user.phone.max'] : 'The phone may not be greater than 20 characters.',
                'phone.not_regex'=> isset($this->messages['user.phone.not_regex']) ? $this->messages['user.phone.not_regex'] : 'The phone must be a valid phone number.',
                'email.required'=> isset($this->messages['user.email.required']) ? $this->messages['user.email.required'] : 'The email field is required.',
                'email.not_regex'=> isset($this->messages['user.email.email']) ? $this->messages['user.email.email'] : 'The email must be a valid email address.',
                'question.required'=> isset($this->messages['user.question.required']) ? $this->messages['user.question.required'] : 'The question field is required.',
                'g-recaptcha-response.required'=> isset($this->messages['user.captcha.required']) ? $this->messages['user.captcha.required'] : 'The captcha field is required.',
                'g-recaptcha-response.recaptcha'=> isset($this->messages['user.captcha.recaptcha']) ? $this->messages['user.captcha.recaptcha'] : 'The registration form is not for robots.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                $item = new Contact;
                $item->name =  $request->name;
                $item->phone =  $request->phone;
                $item->email =  $request->email;
                $item->question =  $request->question;
                $item->save();
                
                return redirect()->route('getContact')->with(['flash_message_succ' => isset($this->messages['user.send_contact_success']) ? $this->messages['user.send_contact_success'] : 'Send contact successful!']);
            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }
}


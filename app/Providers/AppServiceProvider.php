<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use Validator;
use App\Helpers\BatvHelper;
class AppServiceProvider extends ServiceProvider
{
    public function boot(){

        Validator::extend('check_barcode', function($attribute, $value, $parameters, $validator) {
            $barcode = $parameters[0];
            // Kiếm tra xem Barcode có tồn tại trên Server API
            $json = file_get_contents('http://barcode.tohapp.com/barcode_api.php?barcode='.trim($barcode));
            $result = json_decode($json);
            return ( !empty($result->barcode) )?FALSE:TRUE;
        });   

        // Validate email customize
        Validator::extend('not_regex', function($attribute, $value, $parameters, $validator) {
            if (!is_string($value) && !is_numeric($value)) {
                return false;
            }
            return preg_match($parameters[0], $value);
        });

        // Validate email customize
        Validator::extend('not_regex2', function($attribute, $value, $parameters, $validator) {
            if (!is_string($value) && !is_numeric($value)) {
                return false;
            }
            return preg_match($parameters[0], $value);
        });

        // Validate birthday customize
        Validator::extend('validate_birthday', function($attribute, $value, $parameters, $validator) {
            $birthday = $parameters[0];
            $dateCurrent = date('Y-m-d');
            return (BatvHelper::handlingTime($birthday) <= BatvHelper::handlingTime($dateCurrent) ) ? TRUE : FALSE ;
        });

        // Captcha
        Validator::extend('recaptcha', 'App\Validators\ReCaptcha@validate');

        // Validate đơn vị tiền tệ 
        Validator::extend('validate_currency_unit', function($attribute, $value, $parameters, $validator) {
            $currency_unit = strtoupper($parameters[0]);
            $currency_list =  config('batv_config.currency_list');
            $currency_list = array_flip($currency_list);
            return (in_array($currency_unit, $currency_list)) ? TRUE : FALSE ; 
        });
    }



    public function register()
    {
        //
    }
}

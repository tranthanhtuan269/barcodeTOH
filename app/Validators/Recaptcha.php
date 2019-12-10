<?php
namespace App\Validators;

use GuzzleHttp\Client;

class Recaptcha
{
    public function validate($attribute, $value, $parameters, $validator){
        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=>
                [
                    // 'secret'=>'6LdHg0wUAAAAANhIwpQ21e9ARz2JC8vJaryzcJ7B',//local
                    'secret'=>'6LeVh0wUAAAAAIuRGS_cyjET2XbCiWBaNYtPXmLg',//barcodelive.org
                    'response'=>$value
                 ]
            ]
        );
        $body = json_decode((string)$response->getBody());
        return $body->success;
    }
}

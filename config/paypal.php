<?php
return array(
/** set your paypal credential **/
'client_id' =>'AcFjbj63pOKmBcWQfNUm9E75y0iIdVvK6ie8B-IQ7gbWFjba3NQfItoDYnNkjr7hy6AglrRVVF5rMVrk',
'secret' => 'ELArTpbKkgaZcRugcy_MP7c_GWb3Y6nuXT5YEfKxwCBK_OtLCYVSqIe5ChflmnxzivCU--1aP3IDO0Sr',
/**
* SDK configuration 
*/
'settings' => array(
/**
* Available option 'sandbox' or 'live'
*/
'mode' => 'sandbox',
/**
* Specify the max request time in seconds
*/
'http.ConnectionTimeOut' => 1000,
/**
* Whether want to log to a file
*/
'log.LogEnabled' => true,
/**
* Specify the file that want to write on
*/
'log.FileName' => storage_path() . '/logs/paypal.log',
/**
* Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
*
* Logging is most verbose in the 'FINE' level and decreases as you
* proceed towards ERROR
*/
'log.LogLevel' => 'FINE'
),
);
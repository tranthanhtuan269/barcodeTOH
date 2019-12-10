<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model{
    public $timestamps = false;
    protected $table = "payment_history";
    protected $fillable = [
				            'order_name',
				            'amount',
				            'price',
				        ];
    public static function listPaymentHistory($request){

        $dataReturn = PaymentHistory::where("user_id", \Auth::user()->id);
        $table_setting = json_decode(\Auth::user()->payment_table_setting);
        
        if( !empty($request['search']) ) {
            $dataReturn->where('order_name', 'like', '%' . $request['search']['value'] . '%');
        }

        if(isset($table_setting) && count($table_setting) > 0){
            foreach ($table_setting as $setting) {
                if(strlen($setting->sort) > 0){
                    $dataReturn->orderBy($setting->name, $setting->sort);
                }
            }    
        }

        return $dataReturn->get();
    }
}

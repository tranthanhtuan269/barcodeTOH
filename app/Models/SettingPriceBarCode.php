<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\BatvHelper;
use App\Config;

class SettingPriceBarCode extends Model{

    protected $table = "setting_price_barcode";
    protected $fillable =   [
                                'apply_from',
                                'apply_to',
                                'value',
                            ];

    public $timestamps = false;
    
    public static function checkSettingPriceBarCode($id='',$apply_from,$apply_to){
        $result = SettingPriceBarCode::where(function ($query) use ($id){
                    if ($id != '') {
                        $query->whereNotIn('id', [$id]);
                    }     
                })
                ->get();
        $tmp = 0;
        if( $result ){
            foreach ($result as $key => $value) {
                if( BatvHelper::handlingTime( $apply_from ) >= BatvHelper::handlingTime( $value->apply_from ) &&   BatvHelper::handlingTime( $apply_from ) <= BatvHelper::handlingTime( $value->apply_to ) ){
                    $tmp++;
                }elseif( BatvHelper::handlingTime( $apply_to ) >= BatvHelper::handlingTime( $value->apply_from ) &&   BatvHelper::handlingTime( $apply_to ) <= BatvHelper::handlingTime( $value->apply_to )  ){
                    $tmp++;
                }elseif ( BatvHelper::handlingTime( $apply_from ) < BatvHelper::handlingTime( $value->apply_from ) && BatvHelper::handlingTime( $apply_to ) > BatvHelper::handlingTime( $value->apply_to ) ) {
                    $tmp++;
                }
            }
        }
        return $tmp;
    }

    public static function getListPriceBarCode(){
        return SettingPriceBarCode::paginate( config('batv_config.number_page') );
    }


    public static function getPriceBarCode(){
        $time = date('Y-m-d');
        return SettingPriceBarCode::where([
                                        ['apply_from', '<=', $time],
                                        ['apply_to', '>=', $time],
                                    ])->value('value');
    }

}

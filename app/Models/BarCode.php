<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\BatvHelper;
use App\Config;

class BarCode extends Model{

    protected $table = "barcode";
    protected $fillable = [
            'barcode',
            'name',
            'model',
            'manufacturer',
            'image',
            'avg_price',
            'currency_unit',
            'spec',
            'feature',
            'description',
        ];

    public static function checkBarCode($barcode){
        return BarCode::where('barcode',$barcode)->count();
    }

    
    /*
    public static function listBarcodeByUser($request){
        $dataReturn = Barcode::where("user_id", \Auth::user()->id);
        $table_setting = json_decode(\Auth::user()->barcode_table_setting);
        if( !empty($request['search']) ) {
            $dataReturn->where('barcode', 'like', '%' . $request['search']['value'] . '%');
        }
        $dataReturn->select('id', 'barcode', 'name', 'model', 'manufacturer', 'avg_price', 'created_at');
        if(isset($table_setting) && count($table_setting) > 0){
            foreach ($table_setting as $setting) {
                if(strlen($setting->sort) > 0){
                    $dataReturn->orderBy($setting->name, $setting->sort);
                }
            }    
        }
        
        // dd($dataReturn);
        return $dataReturn->get();
    }
    */
    public static function listBarCodebyRequest($request){
        // DB::enableQueryLog();
        $table_setting = json_decode(\Auth::user()->barcode_table_setting);

        if( !empty($request['search']) && strlen($request['search']['value']) > 0 ) {
            $dataReturn = DB::table('barcode')->where('user_id', \Auth::user()->id);
            $dataReturn->select('id', 'barcode', 'name', 'model', 'manufacturer', 'avg_price', 'created_at', 'updated_at');

            $dataReturn2 = DB::table('barcode')->where('user_id', \Auth::user()->id);
            $dataReturn2->select('id', 'barcode', 'name', 'model', 'manufacturer', 'avg_price', 'created_at', 'updated_at');

            $dataReturn->where('barcode', 'like', '%' . $request['search']['value'] . '%');
            $dataReturn2->where('barcode', 'like', $request['search']['value'] . '%');

            $dataReturn2->union($dataReturn);
        }else{
            $dataReturn2 = DB::table('barcode')->where('user_id', \Auth::user()->id);
            $dataReturn2->select('id', 'barcode', 'name', 'model', 'manufacturer', 'avg_price', 'created_at', 'updated_at');
            
            if(isset($table_setting) && count($table_setting) > 0){
                foreach ($table_setting as $setting) {
                    if($setting->name == "barcode" || $setting->name == "avg_price"){
                        if(strlen($setting->sort) > 0){
                            $query = "(" . $setting->name . " * 1) " . $setting->sort;
                            $dataReturn2->orderByRaw($query);
                        }
                    }else{
                        if(strlen($setting->sort) > 0){
                            $dataReturn2->orderBy($setting->name, $setting->sort);
                        }
                    }
                }    
            }
        }
        $data = collect($dataReturn2->get());
        // dd(DB::getQueryLog());
        return $data;
    }

    public static function getBarcodeByUser($user_id){
        $dataReturn = DB::table('barcode')->where('user_id', $user_id);
        $dataReturn->select('id', 'barcode', 'name', 'model', 'manufacturer', 'avg_price', 'created_at', 'updated_at');
        $data = collect($dataReturn2->get());
        return $data;
    }

    public static function listBarCodebyUserDefault($request){
        return BarCode::where('user_id',$request->id)
                ->where(function ($query) use ($request){
                    if ( !empty($request->barcode) ) {
                        $query->where('barcode',trim($request->barcode) );
                    } 
                })
                ->paginate( config('batv_config.number_page') );
    }

    public static function findBarCodebyBarCode($barcode){
        return BarCode::where('barcode',$barcode)->first();
    }

    public static function listBarCodebyId($id,$user_id){
        return BarCode::where('id',$id)->where('user_id',$user_id)->first();
    }

    public static function getBarCodebyId($id){
        return BarCode::where('id',$id)->where('user_id',\Auth::user()->id)->first();
    }

    public static function countBarCodebyUserUnpaid($user_id){
        return BarCode::where('user_id',$user_id)->count();
    }

    public static function deleteMultiBarCodebyUser($id_list){
        $list = explode(",",$id_list);
        $checkBarcodeForUser = Barcode::where("user_id", \Auth::user()->id);
        $checkBarcodeForUser = $checkBarcodeForUser->whereIn('id', $list);
        return ($checkBarcodeForUser->delete() > 0);
    }

}
<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class SettingBarCode extends Model{

    public $timestamps = false;
    protected $table = "setting_barcode";
    protected $fillable = [
					            'number',
					            'price',
					        ];
}

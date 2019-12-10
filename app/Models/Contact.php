<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\BatvHelper;
use App\Config;

class Contact extends Model{
    public $timestamps = false;
    protected $table = "contact";
    
}
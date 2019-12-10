<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Log extends Model{
    public $timestamps = false;
    protected $table = "log";
    
}
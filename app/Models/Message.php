<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "messages";
    protected $fillable = [
            'name',
            'message',
            'category'
        ];

    /*
    	category = 1 is barcode
    	category = 2 is page
    	category = 3 is user
    */
    public $timestamps = false;
}

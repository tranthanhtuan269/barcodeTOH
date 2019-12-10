<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingEmail extends Model
{
    protected $table = "setting_email";
    protected $fillable = [
            'function',
            'user',
            'admin',
            'other'
        ];
        
    public $timestamps = false;
}

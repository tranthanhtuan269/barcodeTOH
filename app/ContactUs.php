<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Helpers\BatvHelper;
use App\Config;

class ContactUs extends Model
{
    protected $table = "contact_uses";
    protected $fillable = [
                            'title',
                            'phone',
                            'email',
                            'content',
                        ];
}

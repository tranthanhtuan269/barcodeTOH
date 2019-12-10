<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Privilegs extends Model
{
    //
    protected $table = "privilegs";
    protected $fillable = [
                            'privilege_name',
                            'router',
                            'parent_id',
                        ];

}

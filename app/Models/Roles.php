<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    //
    protected $table = "roles";
    protected $fillable = [
                            'roles_name',
                            'privileges_id',
                            'status',
                        ];
    
    public function user () {
        return $this->hasMany('App\Models\Roles','role_id');
    }

  	public static function listRoles($request=''){
    	return DB::table('roles')->where(function ($query) use ($request) {
													 if (!empty($request->roles_name)) {
									                    $query->where('roles_name','=',$request->roles_name);
													 }
												})
										    	->orderBy('ID', 'ASC')->get();
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Roles;
use App\Models\Privilegs;
use Validator;

class RoleController extends Controller
{
    //
	/*public function __construct(){
		$this->middleware('auth');
	}*/
	public function getRoleList(Request $request){
        $listRoles = Roles::all()->toArray();
        $results = Roles::listRoles( $request );
        $data = json_decode(json_encode($results), true);
        $arr = array();
        $data2 = Privilegs::all()->toArray();
        foreach ($data2 as $key => $value) {
            $id = $value['id'];
            $arr[$id] = $value['router'];
        }
        $arr2 =array();
        foreach ($data as $key => $value) {
            $arr_id = explode(',', $value['privileges_id']);
            $arr2[] = array_only($arr,$arr_id);
            $data[$key]['privileges_id'] = $arr2[$key];
        }
        return view('layouts_backend.roles.index',['roles'=>$data,'arr_privilegs'=>$data2,'listRoles'=>$listRoles]);
	}

    public function getRoleAdd(){
        $data = Roles::all()->toArray();
        $arr = array();
        $data2 = Privilegs::all()->toArray();
        foreach ($data2 as $key => $value) {
            $id = $value['id'];
            $arr[$id] = $value['router'];
        }
        $arr2 =array();
        foreach ($data as $key => $value) {
            $arr_id = explode(',', $value['privileges_id']);
            $arr2[] = array_only($arr,$arr_id);
            $data[$key]['privileges_id'] = $arr2[$key];
        }
        return view('layouts_backend.roles.add',['roles'=>$data,'arr_privilegs'=>$data2]);
    }

	public function postRoleAdd(Request $request){
		$rules = [
    		'role_name'=>'required|unique:roles,roles_name|min:3',
    	];

    	$messages = [
    		'role_name.required'=> 'Role name is required',
            'roles_name.min'=> 'Role name must have more than 3 characters',
            'role_name.unique' => 'This role name is already in the database.'
    	];

    	$validator = Validator::make($request->all(),$rules,$messages);
        try{
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                $role_name = $request->input('role_name');
                $str="";
                 if (isset($request->check_id)) {
                    $str = implode(',', $request->check_id);
                }
                $roles = new Roles();
                $roles->roles_name = $role_name;
                $roles->privileges_id = $str;
                $roles->save();
                return redirect()->route('getRoleList')->with(['flash_message_succ' => 'Add role successfully!']);
            }
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
	}
	public function getRoleEdit($id){
		$list_privilegs_id = Roles::select('roles_name','privileges_id')->where('id',$id)->first();
        $arr_privilegs_id = explode(',', $list_privilegs_id->privileges_id);
		$data = Privilegs::select('id','privilege_name','parent_id')->get()->toArray();
		return view('layouts_backend.roles.edit',['role_id'=>$id,'data'=>$data,'list_privilegs'=> $arr_privilegs_id, 'role_name' => $list_privilegs_id->roles_name ]);
	}

	public function putRoleEdit(Request $request,$id){
        $this->validate($request,[
            'roles_name' =>'required|min:3',
        ],[
            'roles_name.required'=>'You have not entered role name',
            'roles_name.min'=> 'Role name must have more than 3 characters',
        ]);  
        if ($id != 1) {
            try{
                $roles = Roles::findOrFail($id);
                $str="";
                if (isset($request->check_id)) {
                    $str = implode(',', $request->check_id);
                }
                $roles->roles_name = $request->roles_name;
                $roles->privileges_id = $str;
                $roles->save();
                return redirect()->route('getRoleList')->with(['flash_message_succ' => 'Edit Successfully!']);
            } catch (\Illuminate\Database\QueryException $ex){
                return $ex->getMessage(); 
            }
        }else{
            return redirect()->route('getRoleList')->with(['flash_message_err' => 'You do not have right to edit Role!']);
        } 

	}

    public function deleteRoleDel($id){
        if ($id != 1) {
            $role = Roles::find($id);
            $role->delete($id);
            return redirect()->route('getRoleList')->with(['flash_message_succ' =>'Delete successfully!']);
        }else{
            return redirect()->route('getRoleList')->with(['flash_message_err'=>'You can not delete roles!']);
        }
       
    }

}

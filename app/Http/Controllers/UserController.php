<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Http\Requests;
use App\Models\User;
use App\Models\Roles;
use App\Models\Barcode;
use App\Models\SettingBarCodeFree;

class UserController extends Controller
{
    //
    /*public function __construct(){
    	$this->middleware('auth');
    }*/
    public function getIndex(Request $request){

		
    }

    public function getUserList(Request $request){
        $data_roles = Roles::select('id','roles_name')->get();
        $data = User::getUserList($request);
    	return view('layouts_backend.users.index',['data'=>$data,'data_roles'=>$data_roles]);
    }

    public function getUserAdd(){
        $data = User::with('roles')->paginate(10);//->toArray() ;
        $data_roles = Roles::select('id','roles_name')->get();
        /*$data = User::selectRaw('users.id,users.name,users.email, roles.roles_name')
              ->leftJoin('roles','users.role_id','=','roles.id')->get()->paginate(2);*/
        return view('layouts_backend.users.add',['data'=>$data,'data_roles'=>$data_roles]);
    }

    public function postUserAdd(Request $request){
        try{    
            $this->validate($request,[
                'inputHoten' =>'required|min:3',
                'inputEmail'=>'required|email|unique:users,email',
                'inputPassword'=>'required|min:8|max:32|confirmed',
                'inputPassword_confirmation'=>'required|min:8|max:32',
                'selectRole'=>'required'
            ],[
                // 'inputHoten.required'=>'Bạn chưa nhập tên người dùng',
                // 'inputEmail.required'=>'Bạn chưa nhập email',
                // 'inputPassword.required'=>'Bạn chưa nhập password',
                // 'inputPassword_confirmation.required'=>'Bạn chưa nhập lại password',
                // 'inputPassword.min'=> 'Mật khẩu phải có từ 8 ký tự trở lên',
                // 'inputPassword.max'=>'Mật khẩu phải nhỏ hơn 32 ký tự',
                // 'inputPassword_confirmation.min'=> 'Mật khẩu phải có từ 8 ký tự trở lên',
                // 'inputPassword_confirmation.max'=>'Mật khẩu phải nhỏ hơn 32 ký tự',
                // 'inputPassword.confirmed'=>'Mật khẩu nhập lại chưa đúng',
                // 'inputEmail.email'=>'Bạn chưa nhập đúng định dạng email',
                // 'inputEmail.unique'=>'Email đã tồn tại',
                // 'selectRole.required'=>'Bạn chưa chọn Role',

            ]);  
            $user = new User();
            $user->name = $request->inputHoten; 
            $user->email = $request->inputEmail; 
            $user->password = bcrypt(trim($request->inputPassword)); 
            $user->role_id = $request->selectRole; 
            $user->number_barcode = SettingBarCodeFree::where('id',1)->value('number'); // insert số lượng barcode được tạo Free
            $user->created_by = Auth::user()->id;
            $user->created_at = date('Y-m-d');
            $user->status = 1; 
            $user->save();
            return redirect()->route('getUserList')->with(['flash_message_succ' => 'Thêm người dùng thành công']);
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }
    }
    public function getUserEdit($id){
       // $data = User::with('roles')->where('id',$id)->first();
        $data = User::findOrFail($id);
        $data_roles = Roles::select('id','roles_name')->get();
        return view('layouts_backend.users.edit',['data'=>$data,'data_roles'=>$data_roles]);
    }

    public function putUserEdit(Request $request,$id){
        try{
            $this->validate($request,[
                'inputHoten' =>'required|min:3',
                'inputEmail'=>'required|email|unique:users,email,'.$id,
                'inputPassword'=>'min:8|max:32',
                'inputPassword_confirmation'=>'min:8|max:32|same:inputPassword',
                'selectRole'=>'required'
            ],[
                // 'inputHoten.required'=>'Bạn chưa nhập tên người dùng',
                // 'inputEmail.required'=>'Bạn chưa nhập email',
                // 'inputPassword.required'=>'Bạn chưa nhập password',
                // 'inputPassword_confirmation.required'=>'Bạn chưa nhập lại password',
                // 'inputPassword.min'=> 'Mật khẩu phải có từ 8 ký tự trở lên',
                // 'inputPassword.max'=>'Mật khẩu phải nhỏ hơn 32 ký tự',
                // 'inputPassword_confirmation.min'=> 'Mật khẩu phải có từ 8 ký tự trở lên',
                // 'inputPassword_confirmation.max'=>'Mật khẩu phải nhỏ hơn 32 ký tự',
                // 'inputPassword.confirmed'=>'Mật khẩu nhập lại chưa đúng',
                // 'inputEmail.email'=>'Bạn chưa nhập đúng định dạng email',
                // 'inputEmail.unique'=>'Email đã tồn tại',
                // 'selectRole.required'=>'Bạn chưa chọn Role',

            ]); 
            $user = User::find($id);
            $user->name = $request->inputHoten; 
            $user->email = $request->inputEmail; 
            if ($request->inputPassword != '') {
               $user->password = bcrypt(trim($request->inputPassword)); 
            }
            $user->role_id = $request->selectRole; 
            $user->updated_by = Auth::user()->id;
            $user->updated_at = date('Y-m-d');
            $user->save();
            return redirect()->route('getUserList')->with(['flash_message_succ' => 'Sửa User thành công']);
        } catch (\Illuminate\Database\QueryException $ex){
            return $ex->getMessage(); 
        }

    }
    public function deleteUserDel($id){
        if ($id != 1) {
            $arr = [ 
                    'status'=>0
                ];
            User::updateUser($arr,$id);
            return redirect()->back()->with(['flash_message_succ' =>'Account Deleted']);
        }else{
            return redirect()->back()->with(['flash_message_err'=>'You can not delete Admin account']);
        }
    }

    public function deleteBarcode(Request $request){
        if(isset($request) && isset($request->id)){
            DB::table('barcode')->where('id', '=', $request->id)->delete();
            return response()->json(['status' => '200']);
        }else{
            return response()->json(['status' => '403']);
        }
    }

    public function getBarcodeList(Request $request){
        $id = $request->id;
        $listBarcode = [];
        $listBarcode = DB::table('barcode')->where('user_id', $id)
                        ->select('id', 'barcode', 'name', 'model', 'manufacturer', 'avg_price', 'created_at', 'updated_at')->get();
        return response()->json(['status' => '200', 'listBarcodes' => $listBarcode]);
    }

    public function putUserActive(Request $request){
        if(isset($request->id) && isset($request->active)){
            if ($request->id != 1) {
                User::activeUser($request->id, $request->active);
                return response()->json(['status' => '200']);
            }else{
                return response()->json(['status' => '403']);
            }
        }
    }
}

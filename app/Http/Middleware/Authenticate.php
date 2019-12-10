<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Privilegs;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
               return redirect()->guest('login');
            }
        }
        //echo $request->route()->getName();
        //echo url()->current();
       /* echo $request->segment(1);
        echo $request->segment(2);
        echo $request->segment(3);*/
        $user_id = Auth::user()->id;
        $role_id = Auth::user()->role_id;
        $data =  Roles::where('id', $role_id)->first();
        $arr = explode(',', $data->privileges_id);
        $privileg = new Privilegs();
        $data2 = $privileg::find($arr);
        $route_arr = array();
        foreach ($data2 as $key => $value) {
           $route_arr[] = trim($value->router);
        }
        $route_name = '';
        if ($request->segment(2) != '') {
            $route_name .= $request->segment(2);
        }
        if ($request->segment(3) != '') {
            $route_name .= '-'.$request->segment(3);
        }
        
        if (in_array($route_name, $route_arr) || $request->path() == 'admin' || $request->is('admin/taikhoan/*/'.$user_id) || $request->is('admin/hoso/*/'.$user_id) || $request->is('admin/api/*')){
           //$request->merge(compact('route_arr'));
            view()->share('arr_route', $route_arr);
            return $next($request);
        }else{
           return redirect()->back()->with(['flash_message_err'=>'Bạn không có quyền truy cập chức năng này']);
        }   
      
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\News;

class HomeController extends Controller
{ 
    public function getIndex(Request $request){
    	$route_arr = $request->route_arr;
    	return view('layouts_backend.dashboard',['route_arr'=>$route_arr]);
    }
}

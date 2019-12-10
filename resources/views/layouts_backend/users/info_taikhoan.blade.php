@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<style type="text/css">
	.table-borderless tbody tr td, .table-borderless tbody tr th, .table-borderless thead tr th {
	    border: none;
	}
	.table-borderless {width:200px;}
    @media screen and (min-width: 768px) {
    	.table-borderless {text-align: left;}
    }
</style>
<div class="container-fluid">
	<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
		<h4 class="title-fuction">Account Information</h4>
		@if (session('flash_message_succ') != '')
			<div class="alert alert-success" role="alert">{{ session('flash_message_succ')}}</div>
		@endif
			<div class="col-lg-4 col-md-4">
			 @if(!empty($data->avatar))
				<img style="width:150px;height:150px" src="{{ asset('uploads/users/'.$data->avatar) }}" alt="Avatar">
			 @else
			 	<img style="width:150px;height:150px" src="{{ asset('images/dashboard/avatar.png') }}" alt="Avatar">
			 @endif
			</div>
			<br/>
			<div class="col-lg-8 col-md-8">
			   <table class="table table-borderless" style="min-width: 250px">
			   	 <tr>
			   	 	<td>Name</td>
			   	 	<td>{{ str_limit(Auth::user()->name, $limit = 30, $end = '...') }}</td>
			   	 </tr>
			   	 <tr>
			   	 	<td>Email</td>
			   	 	<td>{{ Auth::user()->email }}</td>
			   	 </tr>
			   	 <tr>
			   	 	<td><a href="{{ route('getTaikhoanEditInfo',['id'=>Auth::user()->id]) }}" class="btn btn-default">Update</a></td>
			
					<td><a href="{{ route('getTaikhoanEditPass',['id'=>Auth::user()->id]) }}" class="btn btn-default">Change Password</a></td>
			   	 </tr>
			   </table>
			</div>
	</div>
	<div class="col-lg-3"></div>
</div>
</div>

@endsection
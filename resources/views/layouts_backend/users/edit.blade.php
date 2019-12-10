@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row">
	
	<div class="col-lg-12">
	    <h4 class="title-fuction">Sửa người dùng</h4>
		@if (session('flash_message_succ') != '')
			<div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
		@endif
	    @if(count($errors) > 0)
	      <div class="alert alert-danger" role="alert">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	      </div>
	    @endif
		<form class="form-horizontal" method="POST" action="{{ url('/') }}/admin/user/edit/{{ $data['id'] }}">
			<input type="hidden" name="_method" value="PUT">
			<div class="form-group">
				<label class="col-sm-3 control-label">Name</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputHoten" id="inputHoten" placeholder="Họ tên" value="{{ old('inputHoten',isset($data['name']) ? $data['name'] : null) }}" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Email</label>
				<div class="col-sm-8">
					<input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Email" value="{{ old('inputEmail',isset($data['email']) ? $data['email'] : null) }}" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Password</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Re-enter Password</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="inputPassword_confirmation" id="inputPassword_confirmation" placeholder="Retype Password">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Role</label>
				<div class="col-sm-8">
					<select name="selectRole" class="form-control" required>
						<option value=""> -- Select Role -- </option>
						@foreach($data_roles as $role)
						<option value="{{ $role->id }}" @if (isset($data['name']) && $role->id == $data['role_id']) selected='selected' @endif @if( old('selectRole') && old('selectRole') == $role->id ) selected='selected' @endif >{{ $role->roles_name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-8">
					<button type="submit" class="btn btn-default">Save</button>
				</div>
			</div>
			{{ csrf_field()}}
		</form>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection
@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
      <h4 class="title-fuction">Create new account</h4>
    
      @include('layouts_backend.notification')
      <form class="form-horizontal" method="POST" action="{{ route('postUserAdd') }}">
        <div class="form-group">
          <label class="col-sm-3 control-label">Name <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="inputHoten" id="inputHoten" placeholder="Họ tên" value="{{ old('inputHoten') }}" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">Email <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Email" value="{{ old('inputEmail')}}" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">Password <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Password" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">Re-enter Password <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="password" class="form-control" name="inputPassword_confirmation" id="inputPassword_confirmation" placeholder="Retype Password" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">Role <span class="required">*</span></label>
          <div class="col-sm-8">
            <select name="selectRole" class="form-control" required>
             <option value=""> -- Select Role -- </option>
            @foreach($data_roles as $role)
                <option value="{{ $role->id }}" @if( old('selectRole') && old('selectRole') == $role->id ) selected='selected' @endif >{{ $role->roles_name }}</option>
            @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-8">
            <button type="submit" class="btn btn-sm btn-orange">Create</button>
          </div>
        </div>
          {{ csrf_field()}}
      </form>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection
@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
	 @if(count($errors) > 0)
	      <div class="alert alert-danger" role="alert">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	      </div>
	    @endif
			<form class="form-horizontal" method="POST" action="{{ url('/') }}/admin/taikhoan/editpass/{{ Auth::user()->id }}">
			<input type="hidden" name="_method" value="PUT">
			<div class="form-group">
				<label for="passwordCurrent" class="col-sm-4 control-label">Current Password</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="passwordCurrent" id="passwordCurrent" value="{{ old('passwordCurrent') }}" required @if ( ($errors->has('inputPassword')==NULL) && $errors->has('inputPassword_confirmation')==NULL ) autofocus @elseif($errors->has('field')) autofocus @endif >
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword" class="col-sm-4 control-label">New Password</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="inputPassword" id="inputPassword" value="{{ old('inputPassword') }}" required @if ($errors->has('inputPassword')) autofocus @endif >
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword_confirmation" class="col-sm-4 control-label">Re-enter New Password</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="inputPassword_confirmation" id="inputPassword_confirmation" value="{{ old('inputPassword_confirmation') }}"  required @if ($errors->has('inputPassword_confirmation')) autofocus @endif >
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" class="btn btn-sm btn-orange">Update</button>
					<a href="{{ route('getTaikhoanInfo',['id'=>Auth::user()->id]) }}" class="btn btn-sm btn-grey">Cancel</a>
				</div>
			</div>
			{{ csrf_field()}}
		</form>
	</div>
	<div class="col-lg-3"></div>
</div>
@endsection
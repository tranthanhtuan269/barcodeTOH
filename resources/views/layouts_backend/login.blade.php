<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('backends/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('backends/css/font-awesome.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('backends/css/dataTables.bootstrap4.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('backends/css/sb-admin.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('backends/css/style.css') }}">
    </head>

    <body class="fixed-nav sticky-footer bg-dark">
		<div class="container">
			<div class="card card-login mx-auto mt-5">
			  <div class="card-header text-center"><h3>Login</h3></div>
			  <div class="card-body">
			    <form action="{{url('login')}}" method="POST">
			      <div class="form-group">
			        <label>Email</label>
			        <input class="form-control" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email" value="{{ old('email') }}">
					@if($errors->has('email'))
						<span class="alert-errors">{{$errors->first('email')}}</span>
					@endif
			      </div>
			      <div class="form-group">
			        <label>Password</label>
			        <input class="form-control" name="password" type="password" placeholder="Password" value="{{ old('password') }}">
					@if($errors->has('password'))
						<span class="alert-errors">{{$errors->first('password')}}</span>
					@endif
			      </div>
			      <div class="form-group">
<!-- 			        <div class="form-check">
			          <label class="form-check-label">
			            <input class="form-check-input" type="checkbox" name="remember"> Remember Password</label>
			        </div> -->
					@if($errors->has('errorLogin'))
						<span class="alert-errors">{{ $errors->first('errorLogin') }}</span>
					@endif
			      </div>
			      {!! csrf_field() !!}
			      <button type="submit" class="btn btn-primary btn-block">Login</button>
			    </form>
<!-- 			    <div class="text-center">
			      <a class="d-block small" href="#">Forgot Password?</a>
			    </div> -->
			  </div>
			</div>
		</div>
        <!--  /.content-wrapper -->
        <script src="{{ asset('backends/js/jquery.min.js') }}"></script>
        <script src="{{ asset('backends/js/bootstrap.min.js') }}"></script>
        {{-- <script src="{{ asset('backends/js/jquery.easing.min') }}"></script> --}}
        <script src="{{ asset('backends/js/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('backends/js/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset('backends/js/sb-admin.min.js') }}"></script>
    </body>
</html>
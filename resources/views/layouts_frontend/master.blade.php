<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>@yield('title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css">
	<link rel="shortcut icon" href="{{{ asset('frontend/images/barcode-favicon.ico') }}}">
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script src="https://unpkg.com/sweetalert2@7.12.16/dist/sweetalert2.all.js"></script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<?php
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	?>
	<meta name="description" content="@yield('description', '')"/>
	<link rel="canonical" href="@yield('canonical', '')">
	
	<meta name="google-site-verification" content="lBrafYhpBGZlRBaiBGKdVLN5vRrtkx3ZENlUPqFJd0o" />

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-151887338-1"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-151887338-1');
	</script>
</head>
<body>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = 'https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.12&appId=948925588474904&autoLogAppEvents=1';
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ url('/') }}">
          	<img src="{{ asset('frontend/images/logo.png') }}" alt="Barcode Logo">
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
			@if ( Auth::check() ) 
				<ul class="nav navbar-nav">
					<li class="{{ Request::is('/')? 'active' : '' }}"><a href="{{ url('/') }}">Home</a></li>
					<li class="{{ Request::is('barcode/list/*') || Request::is('barcode/add') || Request::is('barcode/view/*') || Request::is('barcode/edit/*')? 'active' : '' }}"><a href="{{ route('listBarCodebyUser',['id'=>Auth::user()->id ]) }}">Products</a></li>
					<li class="{{ Request::is('pricing')? 'active' : '' }}"><a href="{{ route('getPricePage')}}">Pricing</a></li>
					@if ( Auth::user()->id == 1 ) 
					<li class="bread-li"><a href="javascript:void(0)">|</a></li>
					<li class="{{ Request::is('tracker.stats.index')? 'active' : '' }}"><a href="/admin/stats?page=summary&days=0">Statistics</a></li>
					<li class="{{ Request::is('getUserList')? 'active' : '' }}"><a href="{{ route('getUserList')}}">Admin</a></li>
					@endif
<!-- 					<li class="{{ Request::is('payment') || Request::is('paymentConfirm') || Request::is('paypal')? 'active' : '' }}"><a href="{{ route('getPayment') }}">Payment</a></li> -->
				</ul>
			@endif
          <ul class="nav navbar-nav navbar-right">
			@if ( Auth::check() ) 
	            <li class="dropdown"><a href="#" class="dropbtn" id="usernametxt">{{ Auth::user()->name }}</a>
				  <div class="dropdown-content">
				    <a href="{{ route('getInfoAccount',['id'=>Auth::user()->id])}}">Profile</a>
				    <a href="#" data-toggle="modal" data-target="#myModalChangePass">Change password</a>
				    <a href="{{ url('logout') }}">Logout</a>
				  </div>
	            </li>
	            <li>
					@if(!empty(Auth::user()->avatar))
				<img style="width: 50px;height: 50px;" src="{{ asset('uploads/users/'.Auth::user()->avatar) }}" alt="{{Auth::user()->name}}">
					@else
						<img style="width: 50px;height: 50px;" src="{{ asset('frontend/images/avartar_default.png') }}" alt="Avatar">
					@endif
	            </li>

			@else
				<li class="default {{ Request::is('add-barcode')? 'active' : '' }}"><a href="{{ route('getAddBarcodePage')}}">Add barcode</a></li>
		        <li class="default {{ Request::is('pricing')? 'active' : '' }}"><a href="{{ route('getPricePage')}}">Pricing</a></li>
		        <li class="default"><a id="loginBtn" class="authenBtn" href="#" data-toggle="modal" data-target="#myModalLogin">Login</a></li>
		        <li class="default">
					<a  id="registerBtn" class="authenBtn" href="#" data-toggle="modal" data-target="#myModalRegister">Register</a>
		        </li>
			@endif

          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

	<!-- Modal Register-->
	<div id="myModalRegister" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content">
	    	<div class="cotainer">
		    	<div class="row">
		    		<div class="item clearfix">
			    		<div class="col-sm-12">
			    			<h3>Register</h3>
			    		</div>
			    		
			    		<div class="col-sm-offset-2 col-sm-8">
						    <form>
						    	<div class="item_detail">
								    <label>Name</label>
								    <input type="text" name="firstname" class="form-control" id="firstname">
							    	<div class="ajax_response firstname"></div>					    		
						    	</div>
						    	<div class="item_detail">
								    <label>Email</label>
								    <input type="email" name="email" class="form-control" id="email">
								    <div class="ajax_response email"></div>
						    	</div>
								<div class="item_detail">
								    <label>Password</label>
								    <input type="password" name="password" class="form-control" id="password">
								    <div class="ajax_response password"></div>
								</div>
								<div class="item_detail">
								    <label>Confirm Password</label>
								    <input type="password" name="confirmpassword" class="form-control" id="confirmpassword">
								    <div class="ajax_response confirmpassword"></div>
								</div>
		                        <div class="item_detail">
		                            <div>
										<div class="g-recaptcha" data-sitekey="6LfM8lIUAAAAAKsfBLVWDRV9ytZ_q7nIBW1J7Hcp"></div> 
		                            </div>
		                        </div>
		                        <?php $terms = \DB::table('page')->find(7) ?>
							    By creating an account you agree to our <a class="terms" href="{{ url('terms-and-conditions') }}" target="_blank">{{$terms->title}}</a>.

							    <div class="ajax_response alert-success" style="display: none"></div>
						    	<div id="pre_ajax_loading_register" style="display: none;text-align: center; margin-bottom: 20px;"><img src="{{ asset('images/general/bx_loader.gif') }}" alt="Loading"></div>
							    <div class="button text-center">
							    	<input type="button" class="register"  onclick="registerAjax()" name="ok" value="Register">
							    	<a class="cancel" href="#" data-toggle="modal" data-target="#myModalRegister">Cancel</a>
							    </div>
						    </form>
			    		</div>
		    		</div>
		    	</div>
	    	</div>
	    </div>
	  </div>
	</div>

	<!-- Modal ChangePass-->
	<div id="myModalChangePass" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content">
	    	<div class="cotainer">
		    	<div class="row">
		    		<div class="item clearfix">
			    		<div class="col-sm-12">
			    			<h3><img src="{{ asset('frontend/images/icon_login.png') }} " alt="Icon Login"> Change password</h3>
			    		</div>
			    		
			    		<div class="col-sm-offset-2 col-sm-8">
						    <form>
						    	<div class="item_detail">
								    <label>Old password</label>
								    <input type="password" class="form-control" id="password_old">
								    <div class="ajax_response password_old"></div>
						    	</div>
						    	<div class="item_detail">
								    <label>New password</label>
								    <input type="password" class="form-control" id="password_change">
								    <div class="ajax_response password"></div>
						    	</div>
								<div class="item_detail">
								    <label>Retype new password</label>
								    <input type="password" class="form-control" id="confirmpassword_change">
								    <div class="ajax_response confirmpassword"></div>
								</div>
							    <div class="ajax_response alert-success" style="display: none"></div>
						    	<div id="pre_ajax_loading_changepass" style="display: none;text-align: center; margin-bottom: 20px;"><img src="{{ asset('images/general/bx_loader.gif') }}" alt="Loading"></div>
							    <div class="button text-center">
							    	<input type="button" class="register" onclick="changepassAjax()" value="Confirm">
							    	<a class="cancel" href="#"  data-dismiss="modal">Cancel</a>
							    </div>
						    </form>
			    		</div>
		    		</div>
		    	</div>
	    	</div>
	    </div>
	  </div>
	</div>

	<!-- Modal Login-->
	<div id="myModalLogin" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content">
	    	<div class="cotainer">
		    	<div class="row">
		    		<div class="item clearfix">
			    		<div class="col-sm-12">
			    			<h3><img src="{{ asset('frontend/images/icon_login.png') }} " alt="Icon Login"> Login</h3>
			    		</div>
			    		
			    		<div class="col-sm-offset-2 col-sm-8">
						    <form>
						    	<div class="item_detail">
								    <label>Email</label>
								    <input type="email" name="email" class="form-control" id="email_login">
								    <div class="ajax_response_login email"></div>
						    	</div>
								<div class="item_detail">
								    <label>Password</label>
								    <input type="password" name="password" class="form-control" id="password_login">
								    <div class="ajax_response_login password"></div>
								</div>
								<div id="pre_ajax_loading" style="text-align: center; margin-bottom: 20px;display: none;"><img src="{{ asset('images/general/bx_loader.gif') }}" alt="Loading"></div>
							    <div class="ajax_response_login alert-error-special" style="display: none"></div>
								<div class="others">
									<p><span>Don't have an account ? </span><span><a href="#" data-toggle="modal" data-target="#myModalRegister" data-dismiss="modal">Sign up here</a></span></p>
									<p>
										<input type="checkbox" name="remember"> Keep my logged in on this computer
									</p>
									<p><a href="#" data-toggle="modal" data-target="#myModalRecoverPass" data-dismiss="modal">Forgot password ?</a></p>
								</div>
							    <div class="button text-center">
							    	<input type="button" class="register" onclick="loginAjax()" value="Login" >
							    	<a class="cancel" href="#"  data-dismiss="modal">Cancel</a>
							    </div>
							    {{ csrf_field() }}
						    </form>
			    		</div>
		    		</div>
		    	</div>
	    	</div>
	    </div>
	  </div>
	</div>


	<!-- Modal RecoverPass-->
	<div id="myModalRecoverPass" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content">
	    	<div class="cotainer">
		    	<div class="row">
		    		<div class="item clearfix">
			    		<div class="col-sm-12">
			    			<h3><img src="{{ asset('frontend/images/icon_login.png') }} " alt="Icon Login"> Forgot Password</h3>
			    		</div>
			    		
			    		<div class="col-sm-offset-2 col-sm-8">
						    <form class="r_email">
						    	<div class="item_detail">
								    <label>Email</label>
								    <input type="email" name="email" class="form-control" id="email_reset">
								    <div class="ajax_response_reset"></div>
						    	</div>
						    	<div id="pre_ajax_loading_resetcode" style="display: none;text-align: center; margin-bottom: 20px;"><img src="{{ asset('images/general/bx_loader.gif') }}" alt="Loading"></div>
							    <div class="button text-center specialrpass">
							    	<input type="button" class="register" onclick="resetCodeAjax()" value="Send reset code">
							    	<a class="cancel" href="#" data-dismiss="modal" id="close-popup">Cancel</a>
							    </div>
						    </form>
			    		</div>
		    		</div>
		    	</div>
	    	</div>
	    </div>
	  </div>
	</div>
	@yield('content')


	<footer>
		<div class="item">
			<div class="container">
				<div class="row">
					<div class="pull-left">
				  		<ul>
				  			{{-- Chuyên mục --}}
				  			<?php $footerDatas = \DB::table('page')->get();
				  					$contactUsData = \DB::table('contact_uses')->find(1);
				  			?>
				  			@foreach ($footerDatas as $footerData) 
								@if ($footerData->slug != "contact-us")
									<li>
										<a href="{!! url('page/' . $footerData->slug) !!}">{{$footerData->title}}</a>
									</li>
								@endif
				  			@endforeach
							<li>
								<a href="{!! url('contact') !!}">{{$contactUsData->title}}</a>
							</li>
						</ul>
					</div>
					<div class="pull-right">
						
					</div>
				</div>
			</div>
		</div>
		<div class="item_2">
			<div class="container">
				<div class="row">
					<div class="text-center">
						Copyrights 2017 ToHSoft Co.,Ltd. All rights reserved.
					</div>
					<div class="text-center">
						v1.15
					</div>
				</div>
			</div>
		</div>
	</footer>
	<div class="ajax_waiting"></div>
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.css') }}">
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="{{ asset('js/general.js') }}"></script>
	<script src="{{ asset('frontend/js/function.js') }}"></script>
	<script>
    	var baseURL="<?php echo URL::to('/'); ?>";
    </script>
</body>
</html>
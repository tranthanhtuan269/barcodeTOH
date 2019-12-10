@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="special"></div>
<div class="account">
	<div class="container">
		<div class="row">
			<div class="item_1 clearfix">
				<h3>Forgot password</h3>
				<p style="padding-top: 10px;">Please check the mailbox, enter the confirmation code
and enter your new password twice to make sure you enter it correctly.</p>
			</div>
			<form class="item_2 clearfix">
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Email
					</div>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="email_user" name="email"  value="{{ Request::get('email') }}" required readonly disabled>
					</div>
				</div>
				<div class="item_2 clearfix">
					<div class="col-sm-3 text-right">
						Code
					</div>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="reset_code" name="reset_code"  value="{{ Request::get('reset_code') }}" required readonly disabled>
					</div>
				</div>
				<div class="item_2 clearfix">
					<div class="col-sm-3 text-right">
						New password
					</div>
					<div class="col-sm-6">
						<input type="password" class="form-control" id="batv_password" >
						<div class="ajax_response password"></div>
					</div>
				</div>
				<div class="item_2 clearfix">
					<div class="col-sm-3 text-right">
						Retype new password
					</div>
					<div class="col-sm-6">
						<input type="password" class="form-control" id="batv_confirmpassword" >
						<div class="ajax_response confirmpassword"></div>
					</div>
				</div>
				<div class="clearfix">
					<div class="col-sm-3 text-right"></div>
					<div class="col-sm-6">
						<div class="ajax_response only" style="display: none"></div>
					</div>
				</div>
				
			    <div class="button text-center clearfix">
			    	<input type="button" class="register" onclick="resetpassAjax()" value="Submit">
			    </div>
			</form>
		</div>
	</div>
</div>
@endsection
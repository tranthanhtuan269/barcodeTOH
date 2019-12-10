@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="special"></div>
<div class="addbarcode-page">
	<div class="container">
		<div class="row">
			<div class="col-sm-7 content-text">
				<h3>INTRODUCE YOUR PRODUCTS</h3>
					<h3 class="pull-right"> WITH THOUSANDS OF DAILY USERS!</h3>
					<div class="clearfix"></div>
				<div class="sub-content">
				We have thousands of daily users online, so you have chance to introduce your products globally and increase your sales gradualy and constantly. Your products haven't appeared on our site yet? Add it right now!
				</div>
			</div>
			<div class="col-sm-4">
				<img src="{{ url('/') }}/frontend/images/tuan.png" width="100%" alt="Introduce Your Products">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-5">
				<img src="{{ url('/') }}/frontend/images/mobile_app.png" width="100%" alt="Introduce Your Products on Mobile Apps">
			</div>
			<div class="col-sm-7 content-text">
				<h3>INTRODUCE YOUR PRODUCTS ON MOBILE APPS</h3>
				<div class="sub-content">
				Not only this website but we also have top-rated mobile apps with millions of active users, you have chance to advertise your products on multi channels. It's really fantastic, isn't it?
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 text-center">
				@if (!Auth::check()) 
					<span class="addbarcode-btn" onclick="loginToAddBarcode()">ADD BARCODE NOW</span>
				@else
					<span class="addbarcode-btn" onclick="addBarcodeNow()">ADD BARCODE NOW</span>
				@endif
			</div>	
		</div>

	</div>
</div>

@endsection
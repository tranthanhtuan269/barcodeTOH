@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')

<div class="special"></div>
<div class="page contact">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3>{{$data->title}}</h3>
				@include('layouts_frontend.notification')
			</div>	
			<div class="col-sm-6">
				<div class="info">
					<span class="email pull-lefft"> <i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:{{$data->email}}">{{$data->email}}</a></span>
					<span class="phone pull-right"><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:{{$data->phone}}">{{ $data->phone }}</a> </span>
					<p class="desc">
						{!! $data->content !!}
					</p>
				</div>
			</div>
			<div class="col-sm-offset-1 col-sm-5">
				<div class="form-questions">
					<h4 class="text-center">Have any questions?</h4>
					<p class="desc">We want to hear from you!  Fill in the form below with any questions or comments and we'll get back to you.</p>
					<form method="post">
	                    <div class="item_detail">
	                        <input type="text" class="form-control" name="name" placeholder="Name..." value="{{ old('name') }}">
	                    </div>
	                    <div class="item_detail">
	                        <input type="text" class="form-control" name="phone" placeholder="+XXX-XXX-XXXXX" value="{{ old('phone') }}">
	                    </div>
	                    <div class="item_detail">
	                        <input type="text" class="form-control" name="email" placeholder="Email..." value="{{ old('email') }}">
	                    </div>
	                    <div class="item_detail">
	                        <textarea name="question" placeholder="Your question or comment..." class="form-control">{{ old('question') }}</textarea>
	                    </div>
                        <div class="item_detail">
							<div class="g-recaptcha" data-sitekey="6LfM8lIUAAAAAKsfBLVWDRV9ytZ_q7nIBW1J7Hcp"></div> 
                        </div>
	                    <div class="button text-center">
	                        <button class="register" id="add-edit-barcode">Send Message</button>
	                    </div>
	                    {{ csrf_field() }}
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
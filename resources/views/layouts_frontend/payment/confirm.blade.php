@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')

<div class="special"></div>
<div class="payment account">
	<div class="container">
		<div class="row">
			@include('layouts_frontend.info_account')
			<h3 class="text-center">Payment confirmation</h3>
			@include('layouts_frontend.notification')
            @if ($message = Session::get('success'))
            <div class="custom-alerts alert alert-success fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                {!! $message !!}
            </div>
            <?php Session::forget('success');?>
            @endif
            @if ($message = Session::get('error'))
            <div class="custom-alerts alert alert-danger fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                {!! $message !!}
            </div>
            <?php Session::forget('error'); ?>
            @endif

			<div class="item clearfix">
				<div class="table-responsive">
					<table class="table table-hover center" style="width:50%;margin: 0 auto;margin-bottom: 35px; ">
					  <thead>
					    <tr>
					      <th>Packages</th>
					      <th>Price</th>
					    </tr>
					  </thead>
					  <tbody>
					  	@if(!empty($data['price']))
	                    <tr>
					      <td>{{ $data['number'] }}</td>
					      <td><span class="price">{{ $data['price'] }}</span> $</td>
					    </tr>
					  	@endif                  
					  </tbody>
					</table> 
					<form action="{{ route('postPaymentConfirm') }}" method="post">
						<div class="text-center">
							<input type="submit" class="register" value="Pay Now!" /> 
							<p>
								<img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/PP_logo_h_200x51.png" alt="Buy now with PayPal" />
							</p>
						</div>   
				        {{ csrf_field() }}   
			        </form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
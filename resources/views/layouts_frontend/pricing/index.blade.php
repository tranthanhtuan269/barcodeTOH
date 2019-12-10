@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="special"></div>
<div class="pricing-page">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center header-page">
				Only one pricing. Plain. Simple.
			</div>	
		</div>
		<div class="row">
			<div class="col-sm-offset-4 col-sm-4">
				<div class="header-hold">
					<p><span class="price">${{ $priceABarcode }}</span> per barcode</p>
					<p>Pay-as-you-go</p>
				</div>
				<div class="body-hold">
					<ul>
						<li><span>{{ $numberBarcodeFree }} first free barcodes</span></li>
						<li><span>Online sign up</span></li>
						<li><span>Muti-channel payment</span></li>
						<li><span>Standard Term of Service</span></li>
					</ul>
					@if (!Auth::check()) 
					<div class="text-center martop">
						<span class="btn-signup" onclick="register()">SIGN UP</span>
					</div>
					@else

						@if(\App\Models\SettingBarCodeFree::where('id', 2)->value('number') == 1 &&
							Auth::user()->number_barcode <= 0)
						<div class="text-center martop">
							<span class="btn-signup" onclick="buyit()">BUY NOW</span>
						</div>
						@else
						<div class="text-center martop">
							<span class="btn-signup" onclick="addBarcodeNow()">ADD BARCODE</span>
						</div>
						@endif
					@endif
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 text-center hot-news">
			HOT NEWS: Currently you can FREE to create barcode. This promotion will end soon. Hurry up!
			</div>	
		</div>

	</div>
</div>

@endsection
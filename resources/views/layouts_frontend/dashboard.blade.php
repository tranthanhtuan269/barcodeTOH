@extends('layouts_frontend.master')

@section('title', 'Information for millions of products globally - Barcode Live')

@section('description')
At Barcode Live, just by typing in the item's barcode number, you can find information for millions of products on the world: photos, name, model, manufacturer and product reviews.
@stop

@section('canonical')
{{ url('/') }}
@stop

@section('content')

@include('layouts_frontend.box_search')

<div class="container">
	<div class="row">
		<div class="box_1">
			<div class="container">
				<div class="row">
					<div class="col-sm-8">
						<div class="item_left">
							<h3>Search information of any products - instantly!</h3>
							<p class="description">
		At Barcode Live, just by typing in the item's barcode number, you can find information for millions of products on the world: photos, name, model, manufacturer and product reviews. With huge database of barcodes and product data as well as from retailers and e-commerce sites, we will bring you the most useful and clearest info on any product you're interested in.
							</p>
						</div>
					</div>
					<div class="col-sm-4">
						<img src="{{ asset('frontend/images/product_search.jpg') }}" alt="Search Infomation Of Any Products">
					</div>
				</div>
			</div>
		</div>
<!-- 		<div class="box_3">
			<div class="container">
				<div class="row">
					<div class="item_childern clearfix">
						<div class="col-sm-6">
							<div class="item_left">
								<h3>Why do a barcode lookup?</h3>
								<p class="description">
		A product's packaging may not tell you everything you need to know about that product — where it comes from, how well it works, or how it's priced at other stores. Enter a barcode from any product into Barcode Lookup, and you'll find all kinds of information about the item — including its manufacturer, name, and description, as well as photos of the product and customer reviews. We'll even show you links to online stores where you can buy the same item — often for less than your local retail store is charging. We're here to make it as easy as possible for you to find what you're looking for, so you can make the right decision about which product to buy.
								</p>
							</div>
						</div>
						<div class="col-sm-offset-1 col-sm-3">
							<img class="special" src="{{ asset('frontend/images/box_3_ma_vach.png') }} ">
						</div>
					</div>

					<div class="item_childern_1 clearfix">
						<div class="col-sm-12">
							<img src="{{ asset('frontend/images/box_4.png') }} ">
							<h3>Where do we get our barcode information?</h3>
						</div>
					</div>
					<div class="item_childern_2 clearfix">
						<div class="col-sm-4">
							<img src="{{ asset('frontend/images/item_1.png') }} ">
						</div>
						<div class="col-sm-8">
							<p>We work directly with over 1,500 retailers, who provide us with massive amounts of data and the latest barcodes in their inventory systems. We've got just about everything in our system — from common grocery items to obscure, lesser known products. We use our own proprietary analysis technology to sort through the data, sift out the most useful information, and present it to you in a searchable, easy-to-use format.</p>
						</div>
					</div>
				</div>
			</div>
		</div> -->
	</div>
</div>
<div class="box_2">
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<div class="row">
					<div class="item_left">
						<div class="item clearfix">
							<div class="col-sm-4">
								<img src="{{ asset('frontend/images/upc.jpg') }}" alt="Universal Product Codes">
							</div>
							<div class="col-sm-8">
								<h4>UPC – Universal Product Codes</h4>
								<p class="description">UPC consists of 12 numeric digits, that are uniquely assigned to each trade item, widely used in the United States, Canada, United Kingdom, Australia, New Zealand, in Europe and other countries for tracking trade items in stores.</p>
							</div>
						</div>
						<div class="item clearfix">
							<div class="col-sm-4">
								<img src="{{ asset('frontend/images/ean.jpg') }}" alt="International (European) Article Number">
							</div>
							<div class="col-sm-8">
								<h4>EAN – International (European) Article Number</h4>
								<p class="description">EAN is a standard describing a barcode symbology and numbering system used in global trade to identify a specific retail product type, in a specific packaging configuration, from a specific manufacturer. The most commonly used EAN standard is the thirteen-digit EAN-13.</p>
							</div>
						</div>
						<div class="item clearfix">
							<div class="col-sm-4">
								<img src="{{ asset('frontend/images/ispn.jpg') }}" alt="International Standard Book Number">
							</div>
							<div class="col-sm-8">
								<h4>ISBN – International Standard Book Number</h4>
								<p class="description">ISBN is a unique numeric commercial book identifier, is assigned to each edition and variation (except reprintings) of a book. Publishers purchase ISBNs from an affiliate of the International ISBN Agency.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-4">
				<div class="item_right">
					<h3>Barcode introduction</h3>
					<p>A barcode is an optical, machine-readable, representation of data; the data usually describes something about the object that carries the barcode. Traditional barcodes systematically represent data by varying the widths and spacings of parallel lines, and may be referred to as linear or one-dimensional (1D). Later, two-dimensional (2D) variants were developed, using rectangles, dots, hexagons and other geometric patterns, called matrix codes or 2D barcodes, although they do not use bars as such. Initially, barcodes were only scanned by special optical scanners called barcode readers. Later, software became available for devices that could read images, such as smartphones with cameras.</p>					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box_app_mobile">
	<div class="container">
		<div class="row">
				<div class="col-sm-8">
					<div class="item_left clearfix">
						<h3 class="text-left">Mobile applications</h3>
						<p>Check out our free mobile apps (both Android and iOS devices) for all the product info in our huge barcode database. These mobile apps give you instant access to the millions of barcodes in our system, along with all the product info: photos, name, model, manufacturer, average price, detailed features and product reviews. Just scan the barcode with phone's camera, app will return accurate result of product you're interested in.</p>
						<div class="item_childern_2 clearfix">
							<a href="https://itunes.apple.com/us/app/qr-code-reader-barcode-scanner-qr-barcode/id1209404877?mt=8"><img src="{{ asset('frontend/images/app_store.png') }}" alt="Available on the App Store"></a>
							<a href="https://play.google.com/store/apps/details?id=com.tohsoft.qrcode"><img src="{{ asset('frontend/images/google_play.png') }}" alt="Android App on Google play"></a>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="item_right">
						<img src="{{ asset('frontend/images/item_3.png') }}" alt="Mobile Scan QR code">
					</div>
				</div>
			

		</div>
	</div>
</div>

<div class="box_social_network">
	<div class="container">
		<div class="row text-center">
			<h3 class="text-center">Share us with friends</h3>
			<div class="col-sm-3">
				<a href="https://www.facebook.com/sharer/sharer.php?u={{ url('/') }}" target="_blank" class="image"><img src="{{ asset('frontend/images/facebook.png') }}" alt="Facebook Share"></a>
			</div>
			<div class="col-sm-3">
				<a href="http://twitter.com/share?text=BarcodeLive&url={{ url('/') }}" class="image"><img src="{{ asset('frontend/images/twitter.png') }}" alt="Twitter Share"></a>
			</div>
			<div class="col-sm-3">
				<a href="https://plus.google.com/share?url={{ url('/') }}" class="image"><img src="{{ asset('frontend/images/google_plus.png') }}" alt="Google Plus Share"></a>
			</div>
			<div class="col-sm-3">
				<a href="mailto:?subject=I wanted you to see this site&amp;body=Check out this site http://barcodelive.org/" class="image"><img src="{{ asset('frontend/images/mail.png') }}" alt="Send Email"></a>
			</div>
		</div>
	</div>
</div>
@endsection

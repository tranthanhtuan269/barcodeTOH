@extends('layouts_frontend.master')

@section('title')
@if (!empty($data->name))
{{$data->name}}
@endif
@if (!empty($data->barcode))
- {{$data->barcode}}
@endif
@stop

@section('description')
@if (!empty($data->name))
{{$data->name}}
@endif
@if (!empty($data->barcode))
- {{$data->barcode}}
@endif
@if (!empty($data->description))
- {{$data->description}}
@else
Description
@endif
@stop

@section('canonical')
@if (!empty($data->name) && !empty($data->barcode))
{{ route('seo-barcode', ['slug' => str_slug($data->name, "-") . '-' . $data->barcode]) }}
@endif
@stop

@section('content')

@include('layouts_frontend.box_search')

<div class="container">
	<div class="row">
		<div class="box_search clearfix">
			@if( !empty($data->barcode) )
			<div class="col-sm-5" style="padding-right: 0px;">
				<div class="image">
                    @if(strlen($data->image) > 0)
                        <?php 
                            $list_file = explode(',', $data->image); 
                            $check_link_http = false;

                            if (count($list_file) == 1 && strpos($data->image, "http") !== false) {
                               $check_link_http = true;
                            }
                        ?>
                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                            @if (count($list_file) > 1)
                                <ol class="carousel-indicators">
                                    @foreach ($list_file as  $key => $image)
                                        <li data-target="#myCarousel" data-slide-to="{{ $key }}" class="@if ($key == 0) active @endif"></li>
                                    @endforeach
                                </ol>
                            @endif
                            <div class="carousel-inner">
                                @if ($check_link_http)
                                    <div class="item active">
                                        <img src="{{ $data->image }}">
                                    </div>
                                @else
                                    @foreach ($list_file as  $key => $image)
                                        <div class="item @if ($key == 0) active @endif">
                                            <img src="{{ asset('uploads/barcode/'.$image) }}" alt="{{ $image }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
    
                            @if (count($list_file) > 1)
                                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            @endif
                        </div>
					@else
						<img src="{{ asset('frontend/images/image_barrcode_df.png') }}">
					@endif
				</div>
			</div>
			<div class="col-sm-7">
				<h1>EAN {!! $data->barcode !!}</h1>
				<div class="title">
					{{ $data->name }}
				</div>
				<div class="info">
					<p>Model: <b>{!! !empty($data->model) ? $data->model : 'n/a' !!}</b></p>
				</div>
				<div class="info">
					<p>Manufacturer: <b>{!! !empty($data->manufacturer) ? $data->manufacturer : 'n/a' !!}</b></p>
				</div>
				<div class="info">
					<p>Price: <b id="numFormatResult">{!! !empty($data->avg_price) ? $data->avg_price : 'n/a' !!}</b> <b> {!! !empty($data->currency_unit) ? $data->currency_unit : 'n/a' !!}</b></p>
				</div>
				<p>Share</p>
				<div class="fb-like" data-href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fbarcodelive.org%2F?barcode={{ $data->barcode }}" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
				<div class="fb-share-button" data-layout="button" data-size="small" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fbarcodelive.org%2F?barcode={{ $data->barcode }}" class="fb-xfbml-parse-ignore">Chia sẻ</a></div>
			</div>

			<div class="col-sm-12">
				<div class="special_search">
					<div class="detail">
						<div class="inner clearfix">
						  <div class="label"><img src="{{ asset('frontend/images/product.png') }}" alt="Descriptions"></div>
						  <h2 class="title">Descriptions</h2>
						</div>
						<div class="show_content">{!! !empty($data->description) ? $data->description : 'n/a' !!}</div>

					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="detail special_hold">
					<div class="inner clearfix">
					  <div class="label"><img src="{{ asset('frontend/images/product.png') }}" alt="Specification"></div>
					  <h2 class="title">Specification</h2>
					</div>
					<div class="show_content">{!! !empty($data->spec) ? $data->spec : 'n/a' !!}</div>

				</div>
			</div>

			<div class="col-sm-6">
				<div class="detail feature_hold">
					<div class="inner clearfix">
					  <div class="label"><img src="{{ asset('frontend/images/product.png') }}" alt="Feature"></div>
					  <h2 class="title">Feature</h2>
					</div>
					<div class="show_content">{!! !empty($data->feature) ? $data->feature : 'n/a' !!}</div>
				</div>
			</div>
			@else
					<div class="alert-danger" style="text-align: center;">No data available</div>
			@endif

		</div>
	</div>
</div>
@endsection

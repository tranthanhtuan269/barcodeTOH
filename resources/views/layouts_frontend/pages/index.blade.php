@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')

<div class="special"></div>
<div class="page">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3>{{ $data->title }}</h3>
				<div class="content">
					{!! $data->content !!}
				</div>
			</div>	
		</div>
	</div>
</div>

@endsection
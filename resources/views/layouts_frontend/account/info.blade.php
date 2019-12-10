@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="special"></div>
<div class="account">
	<div class="container">
		<div class="row">
			<!-- Thông báo -->
			<h3></h3>
			@include('layouts_frontend.notification')
			@include('layouts_frontend.info_account')
			<div class="item_2 clearfix">
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Gender
					</div>
					<div class="col-sm-9">
						<b>
							{{ BatvHelper::getGender($info_account->gender) }}
						</b>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Birthday
					</div>
					<div class="col-sm-9">
						<b>{{ BatvHelper::formatDateStandard('Y-m-d',$info_account->birthday,'d/m/Y') }}</b>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Phone number
					</div>
					<div class="col-sm-9">
						<b>{{ $info_account->phone }}</b>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Address
					</div>
					<div class="col-sm-9">
						<b class="text-too-length">{{ $info_account->address }}</b>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Career
					</div>
					<div class="col-sm-9">
						<b class="text-too-length">{{ $info_account->career }}</b>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Organization 
					</div>
					<div class="col-sm-9">
						<b class="text-too-length">{{ $info_account->organization }}</b>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')

<div class="special"></div>
<div class="payment account">
	<div class="container">
		<div class="row">
			@include('layouts_frontend.info_account')
			<h3>Payment</h3>
			@include('layouts_frontend.notification')
			<div class="item clearfix">
				<center>
					<table class="table">
						<thead class="thead-inverse">
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Value</th>
								<th scope="col">Description</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td align="center" colspan="4"></td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<td><strong><i>Merchant ID </i></strong></td>
								<td>{{ $data['merchantID'] }}</td>
								<td>Được cấp bởi OnePAY</td>
							</tr>
							<tr>
								<td><strong><i>Merchant Transaction Reference</i></strong></td>
								<td>{{ $data['merchTxnRef'] }}</td>
								<td>ID của giao dịch gửi từ website merchant</td>
							</tr>
							<tr>
								<td><strong><i>Transaction OrderInfo</i></strong></td>
								<td>{{ $data['orderInfo'] }}</td>
								<td>Tên hóa đơn</td>
							</tr>
							<tr>
								<td><strong><i>Purchase Amount</i></strong></td>
								<td>{{ $data['amount']/100 }} $</td>
								<td>Số tiền được thanh toán</td>
							</tr>
							<tr>
								<td><strong><i>Transaction Response Code Description </i></strong></td>
								<td style="color: red;font-weight: 600;">{{ BatvHelper::getResponseDescription ( $data['txnResponseCode'] ) }}</td>
								<td>Trạng thái giao dịch</td>
							</tr>
							@if ($data['txnResponseCode'] != "7" && $data['txnResponseCode'] != "No Value Returned") 
				            <tr>
								<td><strong><i>Transaction Number</i></strong></td>
								<td>{{ $data['transactionNo'] }}</td>
								<td>ID giao dịch trên cổng thanh toán</td>
							</tr>
							@endif   
						</tbody>
					</table>
				</center>
			</div>
		</div>
	</div>
</div>

@endsection
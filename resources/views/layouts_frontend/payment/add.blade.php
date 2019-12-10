@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')
<?php 
$setting_table = json_decode(Auth::user()->payment_table_setting); 
function ChangeText($a){
	$a = str_replace("_"," ",$a);
	return ucfirst($a);
}
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.4.1/js/dataTables.colReorder.min.js"></script>
<div class="special"></div>
<div class="payment account">
	<div class="container">
		<div class="row">
			@include('layouts_frontend.info_account')
			<div class="item clearfix">
			        <div class="col-md-12">
			        	@include('layouts_frontend.notification')
			            <!-- Nav tabs -->
			            <div class="card">
			                <ul class="nav nav-tabs" role="tablist">
			                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Payment</a></li>
			                    <li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">Payment History</a></li>
			                </ul>
			                <!-- Tab panes -->
			                <div class="tab-content">
			                    <div role="tabpanel" class="tab-pane active" id="home">
									<form method="get" action="{{ route('paymentConfirm') }}">
					 					<div class="filed clearfix">
					 						<div class="col-sm-3 text-center">
					 							<b>Choose packages</b>
					 						</div>
					 						<div class="col-sm-1">
					 							@if( !empty($setting_barcode) )
													<select name="amount" class="form-control">
					 									@foreach($setting_barcode as $value)
					 										<option value="{{ $value->price }}">{{ $value->number }}</option>
					 									@endforeach
					 								</select>
					 								<script type="text/javascript">
														$('select[name="amount"]').on('change', function() {
					 									  $(".price").text(this.value+' $');
					 									})
					 								</script>
					 							@endif
					 						</div>
					 					</div>
					 					<div class="filed clearfix">
					 						<div class="col-sm-3 text-center">
					 							<b>Total price</b>
					 						</div>
					 						<div class="col-sm-9">
					 							<span class="price" id="pricetxt">
					 								<!-- @if( !empty($setting_barcode) )
					 									{{ $setting_barcode[0]->price }} $ 
					 								@endif -->
					 							</span>
					 						</div>
					 					</div>
										<div class="filed clearfix">
					 						<div class="col-sm-3 text-center"></div>
					 						<div class="col-sm-9">
					 							<input type="submit" class="register" value="Pay Now!" />
					 						</div>
					 					</div>
					 				</form>
			                    </div>
			                    <div role="tabpanel" class="tab-pane" id="history">
									<div>
									    <div class="table-responsive">
											<table id="payment-grid"  cellpadding="0" cellspacing="0" class="toggle-vis" border="0" class="display" width="100%">
										        <thead style="background: #fff;">
										            <tr>
										                <th class="id-field" width="8%">
										                	<span id="select-column-display"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></span>
											                <div class="select-holder-payment hide">
											                	<label class="checkbox">
															    	<input type="checkbox" name="disableCol" id="order_name-checkbox" value="order_name" data-column="order_name-field">Order Name
															    </label>
															    <label class="checkbox">
															    	<input type="checkbox" name="disableCol" id="amount-checkbox" value="amount" data-column="amount-field">Amount
															    </label>
															    <label class="checkbox">
															    	<input type="checkbox" name="disableCol" id="price-checkbox" value="price" data-column="price-field" >Price
															    </label>
															    <label class="checkbox">
															    	<input type="checkbox" name="disableCol" id="created_at-checkbox" value="created_at" data-column="created_at-field">Created
															    </label>
															</div>
														</th>
										                
										                @if(count($setting_table)>0)
															@for($k = 1; $k < count($setting_table); $k++)
											                	<th class="{{ $setting_table[$k]->name }}-field" width="25%">{{ ChangeText($setting_table[$k]->name) }}</th>
															@endfor
														@else
															<th class="order_name-field" width="25%">Order Name</th>
											                <th class="amount-field" width="25%">Amount</th>
											                <th class="price-field" width="15%">Price</th>
											                <th class="created_at-field" width="25%">Created</th>
														@endif
										            </tr>
										        </thead>
											</table>
											<script type="text/javascript">							
												var dataTable = null;
												var table_setting = [];
												var load_first = 0;

											    $(document).ready(function() {

											    	// setting 
											    	$('#pricetxt').html($('select[name="amount"]').val());


											    	var payment_table_setting = '<?php echo json_encode($setting_table); ?>';
											    	// $('input[name*="disableCol"]').prop('checked', true);
											    	$('#select-column-display').click(function(){
											    		if($('.select-holder-payment').hasClass("hide")){
											    			$('.select-holder-payment').removeClass("hide");
											    		}else{
											    			$('.select-holder-payment').addClass("hide");
											    		}
											    	});

											    	$('.select-holder-payment').mouseleave(function() {
													    setTimeout(function() {
													        $('.select-holder-payment').addClass("hide");
													    }, 100);
													});

											    	$('#payment-grid').mouseleave(function() {
													    setTimeout(function() {
													        $('.select-holder-payment').addClass("hide");
													    }, 100);
													});

													// sort
											    	// index
											    	// display

													var dataObject = [
										                    { 
										                    	data: "all",
										                    	class: "all-payment",
											            		orderable: false
										                    },
										                    { 
										                    	data: "order_name",
										                    	class: "order_name-field"
										                    },
											            	{ 
											            		data: "amount",
											            		class: "amount-field"
											            	},
											            	{ 
											            		data: "price",
											            		class: "price-field"
											            	},
											            	{ 
											            		data: "created_at",
											            		class: "created_at-field"
											            	},
										                ];

									                table_setting = JSON.parse(payment_table_setting);
										        	if($(table_setting).length>0){
										        		var first = 0;
										        		var dataObject = [{ 
									                    	data: "all",
									                    	class: "all-payment",
										            		orderable: false
									                    }];
										        		$.each($(table_setting), function( index, value ) {
												        	// sort
						    								if($(this)[0].name.length > 0 && first > 0){
														  		var object = {
														  			data:$(this)[0].name,
														  			class:$(this)[0].name+'-field'
														  		}
														  		dataObject.push(object);
						    								}
						    								first++;
														});
										        	}

											    	var dataJsonSetup = [];

											        dataTable = $('#payment-grid')
											        	.on('init.dt', function () {
												        	table_setting = JSON.parse(payment_table_setting);
												        	if($(table_setting).length>0){
												        		$.each($(table_setting), function( index, value ) {
														        	// sort
								    								if($(this)[0].sort.length > 0){
																  		dataTable.order( [ index, $(this)[0].sort ])
								    									.draw();
								    								}
								    								// display
								    								if($(this)[0].display == 1){
								    									$("#" + $(this)[0].name+ "-checkbox").prop("checked","true");
								    								}else{
								    									$('.' + $(this)[0].name + '-field').addClass("hide");
								    								}
																});	
												        	}else{
												        		$('input[name*="disableCol"]').prop('checked', true);
												        	}
													    })
											        	.DataTable( {
											            // processing: true,
										                serverSide: true,
										                pageLength: 25,
										                aaSorting: [],
										                // stateSave: true,
										                ajax: "{{ url('/') }}/paymentHistoryAjax",
										                columns: dataObject,
										                colReorder: {
													        // fixedColumnsRight: 1,
													        fixedColumnsLeft: 1
													    },
														fnServerParams: function ( aoData ) {
													      	// index
													      	dataJsonSetup = [];
													      	var _cols = aoData.columns;
													      	$.each(_cols, function( index, value ) {
													      		var obj 	= {};
													      		obj.name 	= value.data;
													      		obj.sort 	= '';   // '' is default, asc is asc, desc is desc
													      		obj.display = 1; 	// 1 is show, 0 is hide
													      		dataJsonSetup.push(obj);
													      	});

													      	// order
													      	var _order = aoData.order;
													      	if(_order.length > 0){
													      		dataJsonSetup[_order[0].column].sort = _order[0].dir;
													      	}

													      	if(load_first>0){
														      	// show
														      	$.each($('input[name="disableCol"]'), function( index, value ) {
																  	var column_selected_name = $(this).attr('data-column');
																  	var column_selected_name_sort = '';
															        if($('th.' + column_selected_name).hasClass("hide")){
															        	column_selected_name_sort = column_selected_name.substring(0, column_selected_name.length - 6);
															        	dataJsonSetup.find(x => x.name === column_selected_name_sort).display = 0;
															        }else{
															        	column_selected_name_sort = column_selected_name.substring(0, column_selected_name.length - 6);
															        	dataJsonSetup.find(x => x.name === column_selected_name_sort).display = 1;
															        }
																});

														      	// send to server to save status of table
														      	var data = {
																  	_method		: 'put',
																  	data 		: JSON.stringify(dataJsonSetup)
																};
																$.ajaxSetup(
																{
																  	headers:
																  	{
																  		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
																	}
																});
																$.ajax({
																	type	: "POST",
																	url		: "{{ url('/') }}/putStatePaymentTable",
																	data 	: data,
																	beforeSend: function() {
																	    $("#pre_ajax_loading").show();
																	},
																	complete: function() {
																	    $("#pre_ajax_loading").hide();
																	},
																	success: function (response) {

																	},
																	error: function (data) {
																	 	swal({
																	        html: '<div class="alert-danger">Please check your internet connection and try again.</div>',
																	    });
																	}
																});
															}
															load_first++;
													    },
														fnDrawCallback: function( oSettings ) {
													      	$.each($('input[name*="disableCol"]'), function( index, value ) {
															  	var column_selected_name = $(this).attr('data-column');
														        if($('th.' + column_selected_name).hasClass("hide")){
														        	$('.' + $(this).attr('data-column')).addClass("hide");
														        }
															});

															if ($(".dataTables_empty")[0]) {
															    $('.dataTables_info,.dataTables_paginate,.form-inline').hide();
															} else {
																$('.dataTables_info,.dataTables_paginate,.form-inline').show();
															}

															$('#payment-grid tbody tr.odd td.dataTables_empty').attr('colspan',5);
													    }
											        } );

										        	dataTable.on( 'column-reorder', function ( e, settings, details ) {
													    array_move(dataJsonSetup, details.from, details.to);
													    sendDataToSave(dataJsonSetup);
													});

													function array_move(arr, old_index, new_index) {
													    if (new_index >= arr.length) {
													        var k = new_index - arr.length + 1;
													        while (k--) {
													            arr.push(undefined);
													        }
													    }
													    arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
													    return arr; // for testing
													};

													function containsObject(obj, list) {
													    var i;
													    for (i = 0; i < list.length; i++) {
													        if (list[i] === obj) {
													            return true;
													        }
													    }

													    return false;
													}

													function sendDataToSave(dataJsonSetup){
														$.each($('input[name*="disableCol"]'), function( index, value ) {
														  	var column_selected_name = $(this).attr('data-column');
													        if($('th.' + column_selected_name).hasClass("hide")){
													        	column_selected_name_sort = column_selected_name.substring(0, column_selected_name.length - 6);
													        	dataJsonSetup.find(x => x.name === column_selected_name_sort).display = 0;
													        }else{
													        	column_selected_name_sort = column_selected_name.substring(0, column_selected_name.length - 6);
													        	dataJsonSetup.find(x => x.name === column_selected_name_sort).display = 1;
													        }
														});

													    // send to server to save status of table
												      	var data = {
														  	_method		: 'put',
														  	data 		: JSON.stringify(dataJsonSetup)
														};
														$.ajaxSetup(
														{
														  	headers:
														  	{
														  		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
															}
														});
														$.ajax({
															type	: "POST",
															url		: "{{ url('/') }}/putStatePaymentTable",
															data 	: data,
															beforeSend: function() {
															    $("#pre_ajax_loading").show();
															},
															complete: function() {
															    $("#pre_ajax_loading").hide();
															},
															success: function (response) {

															},
															error: function (data) {
															 	swal({
															        html: '<div class="alert-danger">Please check your internet connection and try again.</div>',
															    });
															}
														});
													}

											    	$('input[name*="disableCol"]').on( 'click', function () {
												        var column_selected_name = $(this).attr('data-column');
												        if($('th.' + column_selected_name).hasClass("hide")){
												        	$('.' + $(this).attr('data-column')).removeClass("hide");
												        }else{
												        	$('.' + $(this).attr('data-column')).addClass("hide");
												        }
												        sendDataToSave(dataJsonSetup);
												    } );

												    $('input[type="search"]').attr("placeholder", "Order name...");
											    } );
											</script>
									    </div>
									</div>

			                	</div>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>

@endsection
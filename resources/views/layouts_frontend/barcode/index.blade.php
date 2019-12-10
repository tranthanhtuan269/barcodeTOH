@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')
<?php 
$setting_table = json_decode(Auth::user()->barcode_table_setting); 
function ChangeText($a){
	$a = str_replace("_"," ",$a);
	return ucfirst($a);
}
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.4.1/js/dataTables.colReorder.min.js"></script>

<div id="special-field" class="special" data-barcode="<?php echo \Auth::user()->getNumberBarcode(); ?>"></div>
<div class="list-barcode account">
	<div class="container">
		<div class="row">
			<h3>Product List</h3>
			@include('layouts_frontend.notification')
			<div class="item_search clearfix">
				<div class="pull-right">
					<div class="add_new_barcode">
						<a id="create-barcode-btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new product</a>
						<!-- <a id="create-barcode-btn">Add new barcode</a> -->
					</div>
				</div>
			</div>

			<div class="detail clearfix">
				<div class="col-sm-12">
					<div class="table-responsive">
						<table id="barcode-grid"  cellpadding="0" cellspacing="0" class="toggle-vis" border="0" class="display" width="100%">
					        <thead>
					            <tr>
					                <th class="id-field" width="8%">
					                	<span id="select-column-display"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></span>
					                	<input type="checkbox" id="select-all-btn" data-check="false">
						                <div class="select-holder hide">
						                	<label class="checkbox">
										    	<input type="checkbox" id="barcode-checkbox" name="disableCol" value="barcode" data-column="barcode-field">Barcode
										    </label>
										    <label class="checkbox">
										    	<input type="checkbox" id="name-checkbox" name="disableCol" value="name" data-column="name-field">Name
										    </label>
										    <label class="checkbox">
										    	<input type="checkbox" id="model-checkbox" name="disableCol" value="model" data-column="model-field">Model
										    </label>
										    <label class="checkbox">
										    	<input type="checkbox" id="manufacturer-checkbox" name="disableCol" value="model" data-column="manufacturer-field">Manufacturer
										    </label>
										    <label class="checkbox">
										    	<input type="checkbox" id="avg_price-checkbox" name="disableCol" value="model" data-column="avg_price-field">Average Price
										    </label>
										    <label class="checkbox">
										    	<input type="checkbox" id="created_at-checkbox" name="disableCol" value="model" data-column="created_at-field">Created At
										    </label>
										    <label class="checkbox">
										    	<input type="checkbox" id="updated_at-checkbox" name="disableCol" value="model" data-column="updated_at-field">Updated At
										    </label>
										</div>
									</th>
									@if(count($setting_table)>0)
										@for($k = 1; $k < count($setting_table)-1; $k++)
						                	<th class="{{ $setting_table[$k]->name }}-field">{{ ChangeText($setting_table[$k]->name) }}</th>
										@endfor
									@else
										<th class="barcode-field">Barcode</th>
						                <th class="name-field">Name</th>
						                <th class="model-field">Model</th>
						                <th class="manufacturer-field">Manufacturer</th>
						                <th class="avg_price-field">Average Price</th>
						                <th class="created_at-field">Created At</th>
						                <th class="updated_at-field">Updated At</th>
									@endif
									<th class="action-field" width="10%">
										Action
									</th>
					            </tr>
					        </thead>
						</table>

						<p>
							<div class="form-inline">
							    <div class="form-group">
								  	<label for="sel1">Action on selected rows:</label>
<!-- 								  	<select class="form-control" id="sel1">
								    	<option>Delete</option>
								  	</select> -->
								  	<div class="btn btn-default" id="apply-all-btn">Delete</div>
								</div> 
							</div>
						</p>
						<script type="text/javascript">							
							var dataTable = null;
							var table_setting = [];
							var dataJsonSetup = [];
							var load_first = 0;
							var barcodeCheckList = [];
							var orderCurrent = '';
							var orderCurrentType = '';
							var searchPrev = 0;
							var searchCurr = 0;

						    $(document).ready(function() {
							    $('#create-barcode-btn').click(function(){
							      var numBarcode = $('#special-field').attr("data-barcode");

							      if(undefined == typeof(numBarcode) || 0 >= numBarcode){
							          swal(settingSweetalerForPayment("{!! $messages_check_buy_barcode !!}"))
							              .then((result) => {
							                  if (result.value) {
							                      window.location.href = baseURL + "/payment";
							                  }
							              });
							      }else{
							        window.location.href = baseURL + "/barcode/add";
							      }
							    });

						    	var barcode_table_setting = '<?php echo json_encode($setting_table); ?>';
						    	$('#select-column-display').click(function(){
						    		if($('.select-holder').hasClass("hide")){
						    			$('.select-holder').removeClass("hide");
						    		}else{
						    			$('.select-holder').addClass("hide");
						    		}
						    	});

						    	$('.select-holder').mouseleave(function() {
								    setTimeout(function() {
								        $('.select-holder').addClass("hide");
								    }, 100);
								});

								$('#barcode-grid').mouseleave(function() {
								    setTimeout(function() {
								        $('.select-holder').addClass("hide");
								    }, 100);
								});

						    	// sort
						    	// index
						    	// display

								var dataObject = [
				                    { 
				                    	data: "all",
				                    	class: "all-barcode",
					            		orderable: false
				                    },
				                    { 
				                    	data: "barcode",
				                    	class: "barcode-field"
				                    },
					            	{ 
					            		data: "name",
					            		class: "name-field"
					            	},
					            	{ 
					            		data: "model",
					            		class: "model-field"
					            	},
					            	{ 
					            		data: "manufacturer",
					            		class: "manufacturer-field"
					            	},
					            	{ 
					            		data: "avg_price",
					            		class: "avg_price-field"
					            	},
					            	{ 
					            		data: "created_at",
					            		class: "created_at-field"
					            	},
					            	{ 
					            		data: "updated_at",
					            		class: "updated_at-field"
					            	},
					            	{ 
					            		data: "action", 
					            		class: "action-field",
					            		orderable: false
					            	},
				                ];

						    	table_setting = JSON.parse(barcode_table_setting);
					        	if($(table_setting).length>0){
					        		var first = 0;
					        		var dataObject = [{ 
				                    	data: "all",
				                    	class: "all-barcode",
					            		orderable: false
				                    }];
					        		$.each($(table_setting), function( index, value ) {
							        	// sort
	    								if($(this)[0].name.length > 0 && (first > 0 && first < $(table_setting).length-1)){
									  		var object = {
									  			data:$(this)[0].name,
									  			class:$(this)[0].name+'-field'
									  		}
									  		dataObject.push(object);
	    								}
	    								first++;
									});

									dataObject.push({ 
					            		data: "action", 
					            		class: "action-field",
					            		orderable: false
					            	});
					        	}

						        dataTable = $('#barcode-grid')
							        .on('init.dt', function () {
							        	table_setting = JSON.parse(barcode_table_setting);
							        	if($(table_setting).length>0){
							        		$.each($(table_setting), function( index, value ) {
									        	// sort
			    								// if($(this)[0].sort.length > 0){
											  		// dataTable.order( [ index, $(this)[0].sort ]).draw();
			    								// }
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
					                aaSorting: [],
					                stateSave: true,
					                ajax: "{{ url('/') }}/barcode/listBarCodeUserAjax",
					                columns: dataObject,
					                pageLength: 25,
					                colReorder: {
								        fixedColumnsRight: 1,
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
												url		: "{{ url('/') }}/barcode/putStateBarcodeTable",
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

										checkCheckboxChecked();
										$.each($('.check-barcode'), function() {
											if($(this).prop('checked')){
												$(this).closest("tr").addClass('highlight');
											} else {
												$(this).closest("tr").removeClass('highlight');
											}
										});
										$('#barcode-grid tbody tr.odd td.dataTables_empty').attr('colspan',8);		    		
								    }
						        } );

        						dataTable.on( 'column-reorder', function ( e, settings, details ) {
								    array_move(dataJsonSetup, details.from, details.to);
								    sendDataToSave(dataJsonSetup);
								    checkCheckboxChecked();
								});

								dataTable.on( 'search.dt', function () {
									if(dataTable.search().length > 0){
										dataTable.order( [] );
										removeOrderClass();
									}
								} );

								//select all checkboxes
								$("#select-all-btn").change(function(){  
								    $('#barcode-grid tbody input[type="checkbox"]').prop('checked', $(this).prop("checked"));
								    // save localstore
								    setCheckboxChecked();
								});

								// function highlightCheckbox() {

								// }

						    	$('body').on('click', '#barcode-grid tbody input[type="checkbox"]', function() {
								    if(false == $(this).prop("checked")){
								        $("#select-all-btn").prop('checked', false); 
								    }
								    if ($('#barcode-grid tbody input[type="checkbox"]:checked').length == $('#barcode-grid tbody input[type="checkbox"]').length ){
								        $("#select-all-btn").prop('checked', true);
								    }

								    // save localstore
								    setCheckboxChecked();
						    	});

						    	$('input[name="disableCol"]').on( 'click', function () {
							        var column_selected_name = $(this).attr('data-column');
							        if($('th.' + column_selected_name).hasClass("hide")){
							        	$('.' + $(this).attr('data-column')).removeClass("hide");
							        }else{
							        	$('.' + $(this).attr('data-column')).addClass("hide");
							        }

							        sendDataToSave(dataJsonSetup);									
							    } );

							    $('input[type="search"]').attr("placeholder", "Barcode...");
							    $('input[type="search"]').addClass('searchTxt');
							    $('input[type="search"]').keydown(function (e) {
							        // Allow: backspace, delete, tab, escape, enter and .
							        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
							             // Allow: Ctrl+A, Command+A
							            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
							             // Allow: home, end, left, right, down, up
							            (e.keyCode >= 35 && e.keyCode <= 40)) {
							                 // let it happen, don't do anything
							                 return;
							        }
							        // Ensure that it is a number and stop the keypress
							        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
							            e.preventDefault();
							        }
							    });
							    $('input[type="search"]').keyup(function (e) {
								    e.preventDefault();
									if(searchPrev == 0 && searchCurr == 0){
							        	searchPrev = searchCurr = $('.searchTxt').val().length;
							        }else{
							        	searchPrev = searchCurr;
							        	searchCurr = $('.searchTxt').val().length;
							        }

							        if(searchCurr == 0 && searchCurr < searchPrev){
							        	// alert(1);
							        	if(orderCurrent.length > 0){
							        		$('.' + orderCurrent + '-field').removeClass('sorting');
								        	$('.' + orderCurrent + '-field').addClass('sorting_' + orderCurrentType);

								        	var index_col = $('.' + orderCurrent + '-field').attr('data-column-index');
								        	dataTable.order( [ index_col, orderCurrentType ]).draw();
								        	
								        	changeSort(orderCurrent, orderCurrentType);
								        	sendDataToSave(dataJsonSetup);
							        	}
							        }
								});			
						    });

							function changeSort( name, sort ) {
							   for (var i in dataJsonSetup) {
							     if (dataJsonSetup[i].name == name) {
							        dataJsonSetup[i].sort = sort;
							        break; //Stop this loop, we found it!
							     }
							   }
							}

							function getCurrentIndex(orderCurrentObj) {
								$.each($('th'), function(key, value){
						    		if($(this).hasClass(orderCurrentObj)){
						    			return key;
						    		}
						    	});
							}

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

							function setCheckboxChecked(){
								barcodeCheckList = [];
								$.each($('.check-barcode'), function( index, value ) {
									if($(this).prop('checked')){
										barcodeCheckList.push($(this).attr("id"));
										$(this).closest("tr").addClass('highlight');
									} else {
										$(this).closest("tr").removeClass('highlight');
									}
								});
							}

							function checkCheckboxChecked(){
								var count_row = 0;
								var listBarcode = $('.check-barcode');
								if(listBarcode.length > 0){
									$.each(listBarcode, function( index, value ) {
										if(containsObject($(this).attr("id"), barcodeCheckList)){
											$(this).prop('checked', 'true');
											count_row++;
										}
									});

									if(count_row == listBarcode.length){
										$('#select-all-btn').prop('checked', true);
									}else{
										$('#select-all-btn').prop('checked', false);
									}
								}else{
									$('#select-all-btn').prop('checked', false);
								}
							}

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
									url		: "{{ url('/') }}/barcode/putStateBarcodeTable",
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
						    
						    function removeItem($id){
								swal(settingSweetaler("{!! $messages_delete_barcode !!}"))
							    .then((result) => {
							      if (result.value) {
							    	var data = {
									  	id:$id,
									  	_method:'delete'
									};
									$.ajaxSetup(
									{
									  	headers:
									  	{
									  		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
										}
									});
									$.ajax({
										type: "POST",
										url: "{{ url('/') }}/barcode/del",
										data: data,
										beforeSend: function() {
										    $("#pre_ajax_loading").show();
										},
										complete: function() {
										    $("#pre_ajax_loading").hide();
										},
										success: function (response) {
											var obj = $.parseJSON(response);
											if(obj.Response == "Success"){
												$.each($('.check-barcode'), function (key, value){
									    			if($(this).prop('checked') == true) {
												    	$('#remove-' + $id).parent().parent().hide("slow");
												    }
									    		});
									    		$('#barcode-grid').DataTable().ajax.reload();
											}else{
												swal({
												    title: "A product does not exist.",
												});
											}
										},
										error: function (data) {
										 	swal({
										        html: '<div class="alert-danger">Please check your internet connection and try again.</div>',
										    });
										}
									});
							      }
							    });
						    }

						    function removeOrderClass(){
						    	$.each($('th'), function(key, value){
						    		if($(this).hasClass('sorting_asc') || $(this).hasClass('sorting_desc')){
								    	var classString = $(this).attr('class');
								    	var classList = classString.split(' ');
								    	orderCurrent = classList[0].substring(0, classList[0].length - 6);
								    	if($(this).hasClass('sorting_asc')){
								    		orderCurrentType = 'asc';
						    				$(this).removeClass('sorting_asc');
								    	}else{
								    		orderCurrentType = 'desc';
						    				$(this).removeClass('sorting_desc');
								    	}
						    			$(this).addClass('sorting');
						    		}
						    	});
						    }

						    $('#apply-all-btn').click(function (){
						      	var $id_list = '';
						    	$.each($('.check-barcode'), function (key, value){
					    			if($(this).prop('checked') == true) {
								    	$id_list += $(this).attr("data-column") + ',';
								    }
					    		});

					    		if ($id_list.length > 0) {
									swal(settingSweetaler("{{ $messages_delete_barcode }}"))
								    .then((result) => {
								      if (result.value) {
									      	var $id_list = '';
									    	$.each($('.check-barcode'), function (key, value){
								    			if($(this).prop('checked') == true) {
											    	$id_list += $(this).attr("data-column") + ',';
											    }
								    		});

								    		if($id_list.length > 0){
										    	var data = {
												  	id_list:$id_list,
												  	_method:'delete'
												};
												$.ajaxSetup(
												{
												  	headers:
												  	{
												  		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
													}
												});
												$.ajax({
													type: "POST",
													url: "{{ url('/') }}/barcode/delMulti",
													data: data,
													beforeSend: function() {
													    $("#pre_ajax_loading").show();
													},
													complete: function() {
													    $("#pre_ajax_loading").hide();
													},
													success: function (response) {
														var obj = $.parseJSON(response);
														if(obj.Response == "Success"){
															$.each($('.check-barcode'), function (key, value){
												    			if($(this).prop('checked') == true) {
															    	$(this).parent().parent().hide("slow");
															    }
												    		});
												    		$('#barcode-grid').DataTable().ajax.reload(); 
														}else{
															swal({
															    title: "A barcode does not exist.",
															});
														}
													},
													error: function (data) {
													 	swal({
													        html: '<div class="alert-danger">Please check your internet connection and try again.</div>',
													    });
													}
												});
											}
									    }
									});
					    		} else {
						            swal({
						              html: '<div class="alert-danger">{!! $messages_check_selected_delete !!}</div>',
						            })
					    		}
						    });
						</script>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
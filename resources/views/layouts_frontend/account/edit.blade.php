@extends('layouts_frontend.master')

@section('title', 'Barcode Live')

@section('content')
<script src="http://jcrop-cdn.tapmodo.com/v0.9.12/js/jquery.Jcrop.min.js"></script>
<div class="special"></div>
<div class="account">
	<div class="container">
		<div class="row">
			<!-- Thông báo -->
			<h3></h3>
			@include('layouts_frontend.notification')
			<form id="changeUserInfo" class="item_2 clearfix" enctype="multipart/form-data" method="post" action="">
				<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="id" value="{{ $info_account->id }}">
			    {{ csrf_field() }}
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Name
					</div>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="name" id="name" value="{{old('name',isset($info_account->name) ? $info_account->name : null )}}" @if ($errors->has('name')) autofocus @endif>
					</div>
					<div class="col-sm-1">
						<span class="character-name"></span></span>/50
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Avartar
					</div>
					<div class="col-sm-6">
					    <div class="avatar">
					    	<img id="image-loading" src="{{ asset('images/general/bx_loader.gif') }}" width="50" height="50">
					    	<input type="hidden" id="avatar" name="avatar" value="{{ $info_account->avatar }}">
					    	@if(strlen($info_account->avatar) > 0)
                            	<img src="{{ url('/') }}/uploads/users/{{ $info_account->avatar }}" id="avatar-image" class="img" alt="Avatar">
                            @else
                            	<img src="{{ url('/') }}/frontend/images/avartar_default.png" id="avatar-image" class="img" alt="Avatar">
                            @endif
					    </div>
                        <div class="btn btn-primary" id="change-avatar-btn">Change Avatar</div>
                        <div class="text-warning"><b>Note: </b>Image should be between 160 x 160 — 3,000 x 3,000 pixels.</div>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Gender
					</div>
					<div class="col-sm-6">
						<input type="radio" name="gender" value="1" @if (old('gender',isset($info_account->gender) ? $info_account->gender : null) == 1) checked="checked" @endif> Male
						<input type="radio" name="gender" value="0" @if (old('gender',isset($info_account->gender) ? $info_account->gender : null) != null && old('gender',isset($info_account->gender) ? $info_account->gender : null) == 0) checked="checked" @endif> Female
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Birthday
					</div>
					<div class="col-sm-6">
						<input type="text" class="form-control"  id="datepicker" name="birthday"  pattern="\d{1,2}/\d{1,2}/\d{4}" value="{{old('birthday',isset($info_account->birthday) ?  BatvHelper::formatDateStandard('Y-m-d',$info_account->birthday,'d/m/Y') : null )}}" @if ($errors->has('birthday')) autofocus @endif>
						<script>
						  $(function() {
						    $( "#datepicker" ).datepicker({
						    		changeMonth: true,
									changeYear: true,
									yearRange: "1950:2020",
									dateFormat: 'dd/mm/yy',
									maxDate: new Date(),
						    	}	
						    );
						  });
					  	</script>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Phone number
					</div>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="phone" placeholder="+XXX-XXX-XXXXX" value="{{old('phone',isset($info_account->phone) ? $info_account->phone : null )}}" @if ($errors->has('phone')) autofocus @endif>
					</div>
					<div class="col-sm-1">
						<span class="character-phone"></span></span>/20
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Address
					</div>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="address"  value="{{old('address',isset($info_account->address) ? $info_account->address : null )}}" @if ($errors->has('address')) autofocus @endif>
					</div>	
					<div class="col-sm-1">
						<span class="character-address"></span></span>/255
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Career
					</div>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="career"  value="{{old('career',isset($info_account->career) ? $info_account->career : null )}}" @if ($errors->has('career')) autofocus @endif>
					</div>
					<div class="col-sm-1">
						<span class="character-career"></span></span>/255
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Organization 
					</div>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="organization"  value="{{old('organization',isset($info_account->organization) ? $info_account->organization : null )}}" @if ($errors->has('organization')) autofocus @endif>
					</div>
					<div class="col-sm-1">
						<span class="character-organization"></span></span>/255
					</div>
				</div>
			    <div class="button text-center clearfix">
			    	<button class="register" id="saveBtn">Submit</button>
			    	<a class="cancel" id="cancelBtn" href="javascript:void(0);" onclick="alertMessage('Are you sure you want to cancel?');">Cancel</a>
			    </div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="change-avatar" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg modal-image">

    <!-- Modal content-->
    <div class="modal-content">
        <form id="form" >
	      	<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal">&times;</button>
	        	<h4 class="modal-title">Select new avatar</h4>
	      	</div>
	      	<div class="modal-body">
	      		<div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%">
                        80%
                    </div>
                </div>
			  	<input id="file" type="file" class="hide" accept="image/*">
			  	<div id="views"></div>
	      	</div>
	      	<div class="modal-footer">
	      		<button type="button" class="btn btn-info" id="load-btn">Load image</button>
	      		<button type="button" class="btn btn-primary hide" id="submit-btn">Submit</button>
	        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      	</div>
		</form>
    </div>
  </div>
</div>

<link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v0.9.12/css/jquery.Jcrop.min.css" type="text/css" />
<script type="text/javascript">
	$(document).ready(function(){
		var $file = null;
	    wordCounter('phone',20);
	    wordCounter('name',255);
	    wordCounter('address',255);
	    wordCounter('career',255);
	    wordCounter('organization',255);

	    $('#change-avatar').on('shown.bs.modal', function (e) {
	    	e.preventDefault();
		  	var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
	        if ($.inArray($($file).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
	            swal({
	                html: '<div class="alert-danger">Only formats are allowed : '+fileExtension.join(', ')+'</div>',
	              })
	            return;
	        }
		  	loadImage($file);
		});

		$("#saveBtn").click(function(){
			// Validate Birthday
			if (!validationDate( $('#datepicker').val() )) {
				swal({
	                html: '<div class="alert-danger">Field birthday is invalid!</div>',
	            });
				return false;
			}

			saveLocalStore();
			$("#changeUserInfo").submit();
		});

		$("#cancelBtn").click(function(){
			localStorage.clear();
		});

		if(localStorage.getItem("formUserData")){
			getLocalStore();
		}
		
		var crop_max_width = 400;
		var crop_max_height = 400;
		var jcrop_api;
		var canvas;
		var context;
		var image;

		var prefsize;

		$('#avatar-image').click(function(){
			$('#file').val("");
			$('#file').click();
		});

		$("#file").change(function() {
			$file = this;
			if($(this).val().length > 0){
				$('.progress').removeClass('hide');
				loadImage(this);
			}
		});

		$('#load-btn').click(function(){
			$('#file').val("");
			$('#change-avatar').modal('hide');
			$('#file').click();
		});

		$('#change-avatar-btn').click(function(){
			$('#file').val("");
			$('#file').click();
		});

		function getLocalStore(){
			var formData = JSON.parse(localStorage.getItem("formUserData"));
			if(typeof $( "input[name='id']" ) != "undefined" && formData.id == $( "input[name='id']" ).val()){
				$( "input[name='name']" ).val(formData.name);
				$( "input[name='avatar']" ).val(formData.avatar);

				(formData.gender == 1)? $( "input[name='gender'][value='1']").prop("checked",true) : $( "input[name='gender'][value='0']").prop("checked",true);
				
				$( "input[name='birthday']" ).val(formData.birthday);
				$( "input[name='phone']" ).val(formData.phone);
				$( "input[name='address']" ).val(formData.address);
				$( "input[name='career']" ).val(formData.career);
				$( "input[name='organization']" ).val(formData.organization);
				$( "#avatar-image" ).attr("src", "{{ url('/') }}/uploads/users/" + formData.avatar);
			}
		}

		function saveLocalStore(){
			var userInfo = {
				id 				: 	$( "input[name='id']" ).val(),
				name 			: 	$( "input[name='name']" ).val(),
				avatar 			: 	$( "input[name='avatar']" ).val(),
				gender 			: 	$( "input[name='gender']:checked" ).val(),
				birthday 		: 	$( "input[name='birthday']" ).val(),
				phone 			: 	$( "input[name='phone']" ).val(),
				address 		: 	$( "input[name='address']" ).val(),
				career 			: 	$( "input[name='career']" ).val(),
				organization 	: 	$( "input[name='organization']" ).val()
			}
			localStorage.setItem("formUserData", JSON.stringify(userInfo));
		}

        function loadImage(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            canvas = null;
            reader.onload = function(e) {
              image = new Image();
              image.onload = validateImage;
              image.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
            $('#submit-btn').removeClass('hide');
          }
        }

        function validateImage() {
        	$('.progress').addClass('hide');
          	if (canvas != null) {
            	image = new Image();
            	image.onload = restartJcrop;
            	image.src = canvas.toDataURL('image/png');

            	$("#form").submit();
          	} else restartJcropOpen();
        }

        function restartJcropOpen() {
            if(image.width < 160 || image.height < 160 || image.width > 3000 || image.height > 3000){
                $("#views").empty();
                swal({
                    html: '<div class="alert-danger">Image must be between 160 x 160 — 3,000 x 3,000 pixels. Please select a different image.</div>',
                });
              }else{
                $('#change-avatar').modal('show');
                restartJcrop();
              }
        }

        function restartJcrop() {
          if (jcrop_api != null) {
            jcrop_api.destroy();
          }
          $("#views").empty();
          $("#views").append("<canvas id=\"canvas\">");
          canvas = $("#canvas")[0];
          context = canvas.getContext("2d");
          canvas.width = image.width;
          canvas.height = image.height;
          var imageSize = (image.width > image.height)? image.height : image.width;
          imageSize = (imageSize > 800)? 800: imageSize;
          context.drawImage(image, 0, 0);
          $("#canvas").Jcrop({
            onSelect: selectcanvas,
            onRelease: clearcanvas,
            boxWidth: crop_max_width,
            boxHeight: crop_max_height,
            setSelect: [0,0,imageSize,imageSize],
            aspectRatio: 1,
            bgOpacity:   .4,
            bgColor:     'black'
          }, function() {
            jcrop_api = this;
          });
          clearcanvas();
          selectcanvas({x:0,y:0,w:imageSize,h:imageSize});
        }

        function clearcanvas() {
          prefsize = {
            x: 0,
            y: 0,
            w: canvas.width,
            h: canvas.height,
          };
        }

        function selectcanvas(coords) {
          prefsize = {
            x: Math.round(coords.x),
            y: Math.round(coords.y),
            w: Math.round(coords.w),
            h: Math.round(coords.h)
          };
        }

		$('#submit-btn').click(function(){
			canvas.width = prefsize.w;
		  	canvas.height = prefsize.h;
		  	context.drawImage(image, prefsize.x, prefsize.y, prefsize.w, prefsize.h, 0, 0, canvas.width, canvas.height);
		  	validateImage();
		});

		$("#form").submit(function(e) {
		  e.preventDefault();
		  $('#change-avatar').modal('hide');
		  formData = new FormData($(this)[0]);
		  // var blob = dataURLtoBlob(canvas.toDataURL('image/png'));
		  //---Add file blob to the form data
		  formData.append("base64", canvas.toDataURL('image/png'));

		  $.ajaxSetup(
		  {
		      headers:
		      {
		      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		  });
		  $.ajax({
		    url: "{{ url('/') }}/images/uploadImage",
		    type: "POST",
		    data: formData,
		    contentType: false,
		    processData: false,
		    beforeSend: function() {
		        $("#image-loading").show();
		    },
		    success: function(data) {
		    	if(data.code == 200){
		    		$('#avatar-image').attr('src', "{{ url('/') }}/uploads/users/" + data.image_url);
		    		$('#avatar').val(data.image_url);
		    		$('#change-avatar').modal('hide');
                    $("#views").empty();
		    	}else{
                    swal({
                        html: '<div class="alert-danger">An error occurred during save process, please try again</div>',
                      })
                    return;
                }
                $('#avatar-image').on('load', function () {
				    $("#image-loading").hide();
				});
		    },
		    error: function(data) {
		    	alert("Error");
		    },
		    complete: function(data) {}
		  });
		});
	});
</script>
@endsection
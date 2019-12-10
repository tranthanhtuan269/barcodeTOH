@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<script src="http://jcrop-cdn.tapmodo.com/v0.9.12/js/jquery.Jcrop.min.js"></script>
<div class="row">
	<div class="col-lg-3"></div>
	<div class="col-lg-6">
		<h4 class="title-fuction">Update Account</h4>
	 @if(count($errors) > 0)
	      <div class="alert alert-danger" role="alert">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	      </div>
	    @endif
			<form class="form-horizontal pull-left" id="formsubmit" method="post" action="{{ url('/') }}/admin/taikhoan/editinfo/{{ Auth::user()->id }}" enctype="multipart/form-data">
			<input type="hidden" name="_method" value="PUT">
			{{ csrf_field()}}
			<div class="form-group">
				<label for="inputName" class="col-sm-4 control-label">Name</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inputName" id="inputName" placeholder="Name" value="{{old('inputName',isset($data->name) ? $data->name : null )}}" required @if ($errors->has('inputName')) autofocus @endif>
				</div>
			</div>
			<div class="form-group clearfix">
				<div class="col-sm-4 text-right">
					Avatar
				</div>
				<div class="col-sm-8">
				    <div class="avatar">
				    	<img id="image-loading" src="{{ asset('images/general/bx_loader.gif') }}" width="50" height="50" style="display: none;position: absolute;top: 35%;left: 45%;">
				    	<input type="hidden" id="avatar" name="avatar" value="{{ $data->avatar }}">
				    	@if(strlen($data->avatar) > 0)
                        	<img src="{{ url('/') }}/uploads/users/{{ $data->avatar }}" id="avatar-image" class="img" alt="Avatar">
                        @else
                        	<img src="{{ url('/') }}/frontend/images/avartar_default.png" id="avatar-image" class="img" alt="Avatar">
                        @endif
				    </div>
                    <div class="btn btn-primary" id="change-avatar-btn">Update avatar</div>
                    <div class="text-warning"><b>Notice: </b>Image should have a size from 160x160 to 3000x3000 pixels</div>
				</div>
			</div>
			<div class="form-group text-center">
				<div>
					<button type="submit" id="btn-submit" class="btn btn-sm btn-orange">Save</button>
					<a href="{{ route('getTaikhoanInfo',['id'=>Auth::user()->id]) }}" class="btn btn-sm btn-grey">Cancel</a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-lg-3"></div>
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
                html: '<div class="alert-danger">Image should have a size from 160x160 to 3000x3000 pixels</div>',
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
                    html: '<div class="alert-danger">There was an error while uploading image</div>',
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

<style type="text/css">
	#avatar-image{
		width: 100%;
	}
	.avatar{
		margin-bottom: 6px;
	}
</style>
@endsection
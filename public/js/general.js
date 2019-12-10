
$(function() {
    $('textarea').each(function() {
        $(this).height($(this).prop('scrollHeight'));
    });
});	

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

$('.pay-type-btn').click(function(){
  if($(this).attr('id') == 'free-btn'){
  	var data = {
  		_method:"put"
	};
	$.ajaxSetup(
	{
	  headers:
	  {
	  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
	});
	$.ajax({
		method: "POST",
		url: baseURL+"/admin/setting/settingBarcodeType",
		data: data,
		dataType: "json",
		beforeSend: function() {
		    $("#pre_ajax_loading").show();
		},
		complete: function() {
		    $("#pre_ajax_loading").hide();
		},
		success: function (response) {
		  var obj = response;
		  if(obj.status=='200'){
		  	$('#charge-btn').removeClass('hide');
		  	$('#free-btn').addClass('hide');
		  	$('input[name=number]').prop('disabled', false);
		  	$('#updateNumberBarcodeFreeBtn').prop('disabled', false);
		  }else{
		    swal({
			    html: '<div class="alert-danger">Login failed. Please check your internet connection and try again.</div>',
			});
		  }
		},
		error: function (data) {
		 swal({
		    html: '<div class="alert-danger">Login failed. Please check your internet connection and try again.</div>',
		  });
		}
	});
  }else if($(this).attr('id') == 'charge-btn'){
  	var data = {
  		_method:"put"
	};
	$.ajaxSetup(
	{
	  headers:
	  {
	  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
	});
	$.ajax({
		method: "POST",
		url: baseURL+"/admin/setting/settingBarcodeType",
		data: data,
		dataType: "json",
		beforeSend: function() {
		    $("#pre_ajax_loading").show();
		},
		complete: function() {
		    $("#pre_ajax_loading").hide();
		},
		success: function (response) {
		  var obj = response;
		  if(obj.status=='200'){
		    $('#charge-btn').addClass('hide');
  			$('#free-btn').removeClass('hide');
  			$('input[name=number]').prop('disabled', true);
  			$('#updateNumberBarcodeFreeBtn').prop('disabled', true);
		  }else{
		    swal({
			    html: '<div class="alert-danger">Login failed. Please check your internet connection and try again.</div>',
			});
		  }
		},
		error: function (data) {
		 swal({
		    html: '<div class="alert-danger">Login failed. Please check your internet connection and try again.</div>',
		  });
		}
	});
  }
  return false;
});

function showEditMessage($message_id){
	var message_data = $('#message-' + $message_id + '>.message-english').html();

	$('#message-' + $message_id + '>.message-english').html('<input type="text" id="message-input-'+ $message_id +'" value="' + message_data + '" class="form-control">');

	onOffEditFunc($message_id, 'On');
}

function saveMessage($message_id){
	var message    = $('#message-input-' + $message_id).val();

	if( message ){
        var data = {
            id: $message_id,
            message: message,
            _method:"put"
        };
        $.ajaxSetup(
        {
            headers:
            {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "POST",
            url: baseURL + "/admin/setting/putSettingMessage",
            data: data,
            dataType: "json",

            success: function(response) {
                if(response.Response=='Error')
                {
                    swal({
		    			html: '<div class="alert-danger">Please check your internet connection and try again.</div>',
		  			});
                }else{
                	swal({
		    			html: '<div class="alert-success font-alert-big">The message has been updated.</div>',
		  			});
                    $('#message-' + $message_id + '>.message-english').attr('data-content', message).html(message);
                    onOffEditFunc($message_id, 'Off');
                }
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    }else{
        swal({
			html: '<div class="alert-danger">The message field is required.</div>',
		});
    }

    return false;
}

function cancelEditMessage($message_id){
	var message    = $('#message-' + $message_id + '>.message-english').attr('data-content');

    $('#message-' + $message_id + '>.message-english').html(message);
	onOffEditFunc($message_id, 'Off');
}

function onOffEditFunc($message_id, $status){
	if($status == 'On'){
		$('#message-' + $message_id + '>.button-special>.item-1').addClass('hide');
		$('#message-' + $message_id + '>.button-special>.item-2').removeClass('hide');
	}else{
		$('#message-' + $message_id + '>.button-special>.item-1').removeClass('hide');
		$('#message-' + $message_id + '>.button-special>.item-2').addClass('hide');
	}
}

function SettingEmail($obj, $id){
	$.ajaxSetup(
    {
        headers:
        {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	if($($obj).hasClass('checkbox-user')){
		var data = {
            id: $id,
            obj: 'user',
            state: $($obj).prop('checked') == true ? 1 : 0,
            _method:"put"
        };
	}else if($($obj).hasClass('checkbox-admin')){
		var data = {
            id: $id,
            obj: 'admin',
            state: $($obj).prop('checked') == true ? 1 : 0,
            _method:"put"
        };
	}else if($($obj).hasClass('checkbox-other')){
		var data = {
            id: $id,
            obj: 'other',
            state: $($obj).prop('checked') == true ? 1 : 0,
            _method:"put"
        };
	}

	$.ajax({
        method: "POST",
        url: baseURL + "/admin/setting/emailSaveConfig",
        data: data,
        dataType: "json",

        success: function(response) {
            if(response.Response=='Error')
            {
                swal({
	    			html: '<div class="alert-danger">Please check your internet connection and try again.</div>',
	  			});
            }
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
}

function activeUser($this, $user_id, $active){
	// console.log($($this).parent().parent().parent().removeClass('danger').addClass('success')); return;
	// var currentPage = window.location.href;
	var data = {
		id 		: $user_id,
		active 	: $active,
		_method : "put"
	};
	$.ajaxSetup({
		headers: {
		  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		method: "POST",
		url: baseURL+"/admin/user/active",
		data: data,
		dataType: 'json',

		success: function (response) {
		    if(response.status=='200'){
		    	$('.alert-success').removeClass('hide');
		    	$('.alert-danger').addClass('hide');
		    	if($active == 'unactive'){
		    		$($this).parent().parent().parent().addClass('danger').removeClass('success');
		    		$($this).addClass('hide');
		    		$($this).parent().find('.hide-user').removeClass('hide');
		    	}else{
		    		$($this).parent().parent().parent().removeClass('danger').addClass('success');
		    		$($this).addClass('hide');
		    		$($this).parent().find('.show-user').removeClass('hide');
		    	}
		    	// window.location = currentPage;
		    }else{
		    	$('.alert-success').addClass('hide');
		    	$('.alert-danger').removeClass('hide');
		    }
		},
		error: function (data) {
		    $('.alert-success').addClass('hide');
		  	$('.alert-danger').removeClass('hide');
		}
	});
}
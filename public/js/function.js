
function formatNumber(nStr, decSeperate, groupSeperate) {
  nStr += '';
  x = nStr.split(decSeperate);
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
  }
  return '$' + (x1 + x2).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
}

function format_curency(nStr){
  nStr = nStr.replace(/,/g, "")
  document.getElementById('numFormatResult').value="";

  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1))
  x1 = x1.replace(rgx, '$1' + ',' + '$2');
  document.getElementById('numFormatResult').value = x1 + x2;
  document.getElementById('result').value = nStr;
}

function format_curency_general(nStr,param_1,param_2){
  nStr = nStr.replace(/,/g, "")
  document.getElementById(param_1).value="";

  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1))
  x1 = x1.replace(rgx, '$1' + ',' + '$2');
  document.getElementById(param_1).value = x1 + x2;
  document.getElementById(param_2).value = nStr;
}

jQuery(document).ready(function(){
	$( ".datepicker" ).datepicker({
    		changeMonth: true,
				changeYear: true,
				yearRange: "1970:2025",
				dateFormat: 'dd/mm/yy'

    	}	
    );
});
jQuery(document).ready(function(){
	var currentDate = new Date();
	$( "#datetimepicker" ).datepicker({
    		changeMonth: true,
			changeYear: true,
			yearRange: "1970:2025",
			dateFormat: 'dd/mm/yy',	
    	}	
    ).datepicker('setDate', currentDate);
    //$( "#datetimepicker" ).datepicker("setDate", "+0");
});

jQuery(document).ready(function(){
	var currentDate = new Date();
	$( "#datetimepicker_special" ).datepicker({
			maxDate: '0',
    		changeMonth: true,
			changeYear: true,
			yearRange: "1970:2025",
			dateFormat: 'dd/mm/yy',	
    	}	
    ).datepicker('setDate', currentDate);

});

jQuery(document).ready(function(){
	$( ".datepicker_special" ).datepicker({
    		changeMonth: true,
				changeYear: true,
				yearRange: "1970:2025",
				dateFormat: 'mm/yy'

    	}	
    );
});

function showModalProduct($id){
  var data = {
      id:$id
  };
  $.ajax({
    url: baseURL+"/admin/user/listBarcode",
    data: data,
    method: "GET",
    dataType:'json',
    success: function (response) {
        var html_data = '';
        if(response.status == 200){
          $.each( response.listBarcodes, function( key, value ) {
            if(key % 2 == 0) 
            html_data += '<tr class="active">';
            else
            html_data += '<tr class=>';  
              html_data += '<td>'+this.id+'</td>';
              html_data += '<td>'+this.barcode+'</td>';
              html_data += '<td>'+this.name+'</td>';
              html_data += '<td>'+this.manufacturer+'</td>';
              html_data += '<td>'+this.avg_price+'</td>';
              html_data += '<td>'+this.model+'</td>';
              html_data += '<td>'+this.created_at+'</td>';
              html_data += '<td>'+this.updated_at+'</td>';
              html_data += '<td><div class="btn btn-danger" onclick="removeBarcode(this, '+this.id+')"><i class="glyphicon glyphicon-remove"></i></div></td>';
            html_data += '</tr>';
          });
          $("#show-data").html(html_data);
        }
    },
    error: function (data) {
      swal({
        html: '<div class="alert-danger">Reset code failed. Please check your internet connection and try again.</div>',
      });
    }
  });
  $('.product-list-modal').modal('show');
  $('.product-list-modal').attr('data-id', $id);
}

function removeBarcode($this, $id){
  $.ajsrConfirm({
    title: "Notice!",
    message: "Are you sure to delete?",
    okButton: "Accept",
    cancelButton: "Cancel",
    onConfirm: function() {
      var data = {
            id:$id,
            _method:'delete'
        };
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          url: baseURL+"/admin/user/deleteBarcode",
          data: data,
          method: "POST",
          dataType:'json',
          success: function (response) {
              var html_data = '';
              if(response.status == 200){
                $('.product-list-modal .message-error').addClass('hide');
                $($this).parent().parent().hide('slow');
              }else{
                $('.product-list-modal .message-error').removeClass('hide');
              }
          },
          error: function (data) {
            swal({
              html: '<div class="alert-danger">Reset code failed. Please check your internet connection and try again.</div>',
            });
          }
        });
        $('.product-list-modal').modal('show');
        $('.product-list-modal').attr('data-id', $id);        
        
    },
    onCancel: function() {

        console.log("Cancel!");
    }

  });
  
}


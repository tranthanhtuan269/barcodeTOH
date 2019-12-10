$(document).ready(function(){
	var url = $('base').attr('href');
	var fullUrl = window.location.href;

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	$('.nav-list').click(function(){
		if($(this).find('.menu-icon-right').hasClass('fa-angle-down')){
			$(this).find('.menu-icon-right').removeClass('fa-angle-down').addClass('fa-angle-right');
		}else{
			$(this).find('.menu-icon-right').addClass('fa-angle-down').removeClass('fa-angle-right');
		}
	});
});

function confimDelete(id) {
    $.ajsrConfirm({
        message: "Are you sure want to delete?",
        onConfirm: function() {
          $('form#confirm-delete-'+id).submit();
        },
        nineCorners: false,
    });
    return false;

}
function searchFormJs() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("search-form-js");
  filter = input.value.toUpperCase();
  table = document.getElementById("group-table");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function closePopup() {
  $("#btn-cancel").trigger("click");
}

$(document).keydown(function(e) {

  if (e.keyCode == 27) {
    $("#btn-cancel").trigger("click");
  }

});
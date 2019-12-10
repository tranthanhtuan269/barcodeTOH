$(document).on('hidden.bs.modal', function (event) {
  if ($('.modal:visible').length) {
    $('body').addClass('modal-open');
  }
});

$('.authenBtn').click(function () {
  CheckStatusInternet();
});

// Validate Date
function validationDate(dateString) {
  if (dateString == "") return true;
  if (!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString))
    return false;

  var parts = dateString.split("/");
  var day = parseInt(parts[0], 10);
  var month = parseInt(parts[1], 10);
  var year = parseInt(parts[2], 10);

  if (year < 1000 || year > 3000 || month == 0 || month > 12)
    return false;

  var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

  if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
    monthLength[1] = 29;

  return day > 0 && day <= monthLength[month - 1];
}

function wordCounter(id, max_length, special = false) {
  var count = $('input[name="' + id + '"]').val().length;
  $('.character-' + id).html(count);
  if (!special) {
    if (count > max_length) {
      $('span.character-' + id).addClass('toh-warning');
    } else {
      $('span.character-' + id).removeClass('toh-warning');
    }
  } else {
    if (count == max_length || count == max_length - 1) {
      $('span.character-' + id).removeClass('toh-warning');
    } else {
      $('span.character-' + id).addClass('toh-warning');
    }
  }
}


$('input[name="barcode"]').keyup(function () {
  wordCounter('barcode', 13, true);
});

$('#name-barcode').keyup(function () {
  wordCounter('name', 200);
});

$('input[name="model"]').keyup(function () {
  wordCounter('model', 50);
});

$('input[name="manufacturer"]').keyup(function () {
  wordCounter('manufacturer', 200);
});

$('input[name="avg_price_tmp"]').keyup(function () {
  format_curency(this.value);
  wordCounter('avg_price', 100);
});

$('input[name="phone"]').keyup(function () {
  wordCounter('phone', 20);
});

$('#name').keyup(function () {
  wordCounter('name', 255);
});

$('input[name="address"]').keyup(function () {
  wordCounter('address', 255);
});

$('input[name="career"]').keyup(function () {
  wordCounter('career', 255);
});

$('input[name="organization"]').keyup(function () {
  wordCounter('organization', 255);
});

function settingSweetaler(notification) {
  return {
    title: notification,
    // text: "Are you sure?",
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'YES',
    cancelButtonText: 'NO',
    confirmButtonClass: 'btn btn-success',
    cancelButtonClass: 'btn btn-danger',
  };
}

function settingSweetalerForPayment(notification) {
  return {
    title: notification,
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'BUY NOW',
    cancelButtonText: 'LATER',
    confirmButtonClass: 'btn btn-success',
    cancelButtonClass: 'btn btn-danger',
  };
}

$('form#form-delete').on('submit', function (e) {
  e.preventDefault();
  var form = this;
  swal(settingSweetaler("Are you sure want to delete?"))
    .then((result) => {
      if (result.value) {
        form.submit();
      }
    });
});

function alertMessage(param) {
  swal(settingSweetaler(param))
    .then((result) => {
      if (result.value) {
        window.location.href = baseURL;
      }
    });
}

function loginAjax() {
  var email = $('#email_login').val();
  var password = $('#password_login').val();
  var remember = $('input[name=remember]').prop('checked');
  var data = {
    email: email,
    password: password,
    remember: remember,
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
    url: baseURL + "/loginAjax",
    data: data,
    dataType: 'json',
    beforeSend: function () {
      $("#pre_ajax_loading").show();
    },
    complete: function () {
      $("#pre_ajax_loading").hide();
    },
    success: function (response) {
      if (response.Response == 'Error') {
        var check = response.Message;
        if (typeof check == "string") {
          swal({
            html: '<div class="alert-danger">' + response.Message + '</div>',
          })
        } else {
          var errors = '';
          $.each(response.Message, function (key, value) {
            errors += '<div class="alert-danger">' + value + '</div>';
          });
          swal({
            html: errors,
          })
        }
      } else {
        var url = window.location.href;
        if (url.indexOf("add-barcode") >= 0) {
          window.location = baseURL + '/barcode/add';
        } else {
          window.location = baseURL;
        }
      }
    },
    error: function (data) {
      swal({
        html: '<div class="alert-danger">Login failed. Please check your internet connection and try again.</div>',
      });
    }
  });

  return false;
}

function registerAjax() {
  var firstname = $('#firstname').val();
  var email = $('#email').val();
  var password = $('#password').val();
  var confirmpassword = $('#confirmpassword').val();
  var data = {
    firstname: firstname,
    email: email,
    password: password,
    confirmpassword: confirmpassword,
    captcha: grecaptcha.getResponse(),
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
    url: baseURL + "/registerAjax",
    data: data,
    beforeSend: function () {
      $("#pre_ajax_loading_register").show();
    },
    complete: function () {
      $("#pre_ajax_loading_register").hide();
    },
    success: function (response) {
      var obj = $.parseJSON(response);
      if (obj.Response == 'Error') {
        grecaptcha.reset();
        var errors = '';
        $.each(obj.Message, function (key, value) {
          errors += '<div class="alert-danger">' + value + '</div>';
        });
        swal({
          dangerMode: true,
          html: errors,
        })
      } else {
        swal({
          html: '<div class="alert-success">' + obj.Message + '</div>',
        })
          .then((result) => {
            if (result.value) {
              var url = window.location.href;
              if (url.indexOf("add-barcode") >= 0) {
                window.location = baseURL + '/barcode/add';
              } else {
                window.location = baseURL;
              }
            }
          });
      }

    },
    error: function (data) {
      swal({
        html: '<div class="alert-danger">Registration failed. Please check your internet connection and try again.</div>',
      });
    }
  });
  return false;
}

function changepassAjax() {
  var password_old = $('#password_old').val();
  var password = $('#password_change').val();
  var confirmpassword = $('#confirmpassword_change').val();
  var data = {
    password_old: password_old,
    password: password,
    confirmpassword: confirmpassword,
    _method: "put"
  };
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    method: "POST",
    url: baseURL + "/account/changepassAjax",
    data: data,
    beforeSend: function () {
      $("#pre_ajax_loading_changepass").show();
    },
    complete: function () {
      $("#pre_ajax_loading_changepass").hide();
    },
    success: function (response) {
      var obj = $.parseJSON(response);
      if (obj.Response == 'Error') {
        $.each(obj.Message, function (key, value) {
          var errors = '';
          $.each(obj.Message, function (key, value) {
            errors += '<div class="alert-danger">' + value + '</div>';
          });
          swal({
            html: errors,
          })
        });
      } else {
        swal({
          html: '<div class="alert-success">' + obj.Message + '</div>',
        })
          .then((result) => {
            if (result.value) {
              window.location.href = baseURL;
            }
          });
      }

    },
    error: function (data) {
      swal({
        html: '<div class="alert-danger">Change password failed. Please check your internet connection and try again.</div>',
      });
    }
  });
  return false;
}

function resetCodeAjax() {
  var email = $('#email_reset').val();
  var data = {
    email: email,
  };
  $.ajax({
    url: baseURL + "/resetCodeAjax",
    data: data,
    beforeSend: function () {
      $("#pre_ajax_loading_resetcode").show();
    },
    complete: function () {
      $("#pre_ajax_loading_resetcode").hide();
    },
    success: function (response) {
      var obj = $.parseJSON(response);
      if (obj.Response == 'Error') {
        var check = obj.Message;
        if (typeof check == "string") {
          swal({
            html: '<div class="alert-danger">' + obj.Message + '</div>',
          })
        } else {
          $.each(obj.Message, function (key, value) {
            var errors = '';
            $.each(obj.Message, function (key, value) {
              errors += '<div class="alert-danger">' + value + '</div>';
            });
            swal({
              html: errors,
            })
          });
        }
      }
      else {
        swal({
          html: '<div class="alert-success">' + obj.Message + '</div>',
        })
          .then((result) => {
            if (result.value) {
              $("#close-popup").trigger('click');
            }
          });
      }
    },
    error: function (data) {
      swal({
        html: '<div class="alert-danger">Reset code failed. Please check your internet connection and try again.</div>',
      });
    }
  });
  return false;
}

function resetpassAjax() {
  var email = $('#email_user').val();
  var reset_code = $('#reset_code').val();
  var password = $('#batv_password').val();
  var confirmpassword = $('#batv_confirmpassword').val();
  var data = {
    email: email,
    reset_code: reset_code,
    password: password,
    confirmpassword: confirmpassword,
    _method: "put"
  };
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    method: "POST",
    url: baseURL + "/resetpassAjax",
    data: data,

    success: function (response) {
      var obj = $.parseJSON(response);
      if (obj.Response == 'Error') {
        var check = obj.Message;
        if (typeof check == "string") {
          swal({
            html: '<div class="alert-danger">' + obj.Message + '</div>',
          })
        } else {
          $.each(obj.Message, function (key, value) {
            var errors = '';
            $.each(obj.Message, function (key, value) {
              errors += '<div class="alert-danger">' + value + '</div>';
            });
            swal({
              html: errors,
            })
          });
        }
      }
      else {
        swal({
          html: '<div class="alert-success">' + obj.Message + '</div>',
        })
      }
    },
    error: function (data) {
      swal({
        html: '<div class="alert-danger">Reset password failed. Please check your internet connection and try again.</div>',
      });
    }
  });
  return false;
}


// $("body").on("click", "#add-edit-barcode", function (e) {
//   var barcode = $('input[name="barcode"]').val();
//   var name = $('input[name="name"]').val();
//   var model = $('input[name="model"]').val();
//   var manufacturer = $('input[name="manufacturer"]').val();
//   var currency_unit = $('input[name="currency_unit"]').val();
//   var spec = CKEDITOR.instances.spec.getData();
//   var feature = CKEDITOR.instances.feature.getData();
//   var description = CKEDITOR.instances.description_field.getData();

//   var file_list = $('#Filelist li img.add-new').map(function (idx, elem) {
//     return $(elem).attr('src');
//   }).get()
//   console.log(file_list);
//   // var file_list_title = $('#Filelist li img.add-new').map(function (idx, elem) {
//   //   return $(elem).attr('title');
//   // }).get()


//   var options = {
//     data: {
//       barcode: barcode,
//       name: name,
//       model: model,
//       manufacturer: manufacturer,
//       currency_unit: currency_unit,
//       spec: spec,
//       feature: feature,
//       description: description,
//       file_list: file_list,
//       // file_old_deleted: file_old_deleted,
//       // form_data: form_data,
//       // file_list_title: file_list_title,
//     },
//     beforeSend: function () {
//       $("#pre_ajax_loading_barcode").show();
//     },
//     complete: function (response) {
//       $("#pre_ajax_loading_barcode").hide();
//       if ($.isEmptyObject(response.responseJSON.error)) {
//         var urlRedirect = baseURL + "/barcode/list/" + response.responseJSON.user_id;
//         swal({
//           html: '<div class="alert-success">' + response.responseJSON.success + '</div>',
//         })
//           .then((result) => {
//             window.location.href = urlRedirect;
//           });
//       } else {
//         var errors = '';
//         if (typeof response.responseJSON.error == "string") {
//           swal(settingSweetalerForPayment(response.responseJSON.error))
//             .then((result) => {
//               if (result.value) {
//                 window.location.href = baseURL + "/payment";
//               }
//             });
//         } else {
//           $.each(response.responseJSON.error, function (key, value) {
//             errors += '<div class="alert-danger">' + value + '</div>';
//           });
//           swal({
//             html: errors,
//           })
//         }
//       }
//     }
//   };
//   $(this).parents("form").ajaxForm(options);
// })

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

function format_curency(nStr) {

  if (nStr.length > 133) {
    document.getElementById('numFormatResult').value = nStr.substr(0, nStr.length - 1);
    return;
  }

  nStr = nStr.replace(/,/g, "");
  document.getElementById('numFormatResult').value = "";

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


// create by Tuantt
function format_curency_global(obj) {
  // obj is id of input value
  nStr = $('#' + obj).html();
  nStr = nStr.replace(/,/g, "");

  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1))
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
  $('#' + obj).html(x1 + x2);
}

function format_curency_general(nStr, param_1, param_2) {
  nStr = nStr.replace(/,/g, "")
  document.getElementById(param_1).value = "";

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

$(document).ready(function () {
  if (window.location.href.indexOf("?barcode=") > -1) {
    format_curency_global('numFormatResult');
  }

  if (window.location.href.indexOf("barcode/edit") > -1) {
    format_curency($('#numFormatResult').val());
  }

  // if (window.location.href.indexOf("barcode/list") > -1) {
  //   $('#create-barcode-btn').click(function(){
  //     var numBarcode = $('#special-field').attr("data-barcode");

  //     if(undefined == typeof(numBarcode) || 0 >= numBarcode){
  //         swal(settingSweetalerForPayment("You do not have any barcode creations, please buy more!"))
  //             .then((result) => {
  //                 if (result.value) {
  //                     window.location.href = baseURL + "/payment";
  //                 }
  //             });
  //     }else{
  //       window.location.href = baseURL + "/barcode/add";
  //     }
  //   });

  // }


  $('#myModalRegister').on('hide.bs.modal', function () {
    location.reload();
  });

  $('#myModalRegister').on('show.bs.modal', function () {
    $('#myModalRegister input[type="email"],#myModalRegister input[type="password"],#myModalRegister input[type="text"]').val('');
  });

  $('#myModalRecoverPass').on('show.bs.modal', function () {
    $('#myModalRecoverPass input[type="email"]').val('');
  });
  // $('[class="cancel"]').click(function(){
  //   $('input[type="number"],input[type="text"],input[type="text"],input[type="email"],input[type="password"],textarea').val('');
  //     $('.alert-danger,.alert-success,.alert-error-special').html('');
  //     $('input[type="checkbox"]').prop('checked', false);
  //   $("select").val("0");
  // });

  // Không cho nhập dấu cách
  $('input[type="password"],input[type="email"],input[type="number"]').on({
    keydown: function (e) {
      if (e.which === 32)
        return false;
    },
    change: function () {
      this.value = this.value.replace(/\s/g, "");
    }
  });

  $('#email_login,#password_login,input[name="remember"]').keypress(function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
      loginAjax();
      return false;
    }
  });
  $('#email_reset').keypress(function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
      resetCodeAjax();
      return false;
    }
  });

  $('#firstname,#email,#password,#confirmpassword').keypress(function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
      registerAjax();
      return false;
    }
  });
});

$('#barcodetxt').keydown(function (e) {
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

function CheckStatusInternet() {
  if (!navigator.onLine) {
    window.location.href = baseURL;
  }
}

function register() {
  $('#myModalRegister').modal('show');
}

function loginToAddBarcode() {
  $('#myModalRegister').modal('show');
}

function addBarcodeNow() {
  window.location.href = baseURL + "/barcode/add";
}

function buyit() {
  window.location.href = baseURL + "/payment";
}
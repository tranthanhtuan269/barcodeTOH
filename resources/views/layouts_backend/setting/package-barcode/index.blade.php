@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row content-function">
<?php
        // echo "<pre>";
        // print_r($data);die;
?>
  <!-- Danh muc -->
  @include('layouts_backend.setting.menuleft')
  <div class="col-lg-10">
    <h4 class="title-fuction">Barcode Package List</h4>
    <div class="table-responsive">
      <div class="form_style">
        <div id="comment-list-box">
          <div class="form_style">
              <div id="comment-list-box">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Package</th>
                            <th class="text-center">Price ($)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="special">
                      @foreach($data as $v)
                        <tr class="message-box text-center" id="message_{{ $v->id }}">
                          <td class="message-salary">{{ $v->number }}</td>
                          <td class="message-welfarefund">{{ $v->price }}</td>
                          <td class="button_special">
                            <div class="item_1">
                              <a class="btnEditAction" name="edit" onClick="showEditBox(this,{{ $v->id }})"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                              <a class="btnDeleteAction" name="delete" onClick="callCrudAction('delete',{{ $v->id }})"><i class="fa fa-times" aria-hidden="true" style="color:red; padding-left: 5px;"></i></a>
                            </div>
                            <div class="item_2"></div>

                          </td>
                        </tr>
                      @endforeach
                  
                    </tbody>
                </table>
              </div>
            <div id="frmAdd">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Package</th>
                            <th class="text-center">Price ($)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                     <tr>
                      <td><input name="salary_basic" id="salary_basic" class="form-control" required></td>
                      <td><input name="welfarefund" id="welfarefund" class="form-control" required></td>
                      <td><p><a id="btnAddAction" name="submit" onClick="callCrudAction('add','')" class="btn btn-sm btn-orange">Thêm mới</a></p></td>
                    </tr>
                  
                    </tbody>
                </table>
              
              
            </div>
            <img src="{{ asset('images/general/bx_loader.gif') }}" style="display:none">
          </div>
        </div>
    </div>
  </div>

<script type="text/javascript">
  function showEditBox(editobj,id) {
    $('#frmAdd').hide();
    // $(editobj).prop('disabled','true');
    var messageSalary = $("#message_" + id + " .message-salary").html();
    var messageWelfarefund = $("#message_" + id + " .message-welfarefund").html();
    var salary = '<input type="text" value="'+messageSalary+'" id="salary_basic_'+id+'" class="form-control">';
    var welfarefund =  '<input type="text" value="'+messageWelfarefund+'" id="welfarefund_'+id+'" class="form-control">';
    var button = '<button name="ok" onClick="callCrudAction(\'edit\','+id+')" class="btn btn-xs btn-orange">Lưu</button> <button name="cancel" onClick="cancelEdit(\''+messageSalary+'\',\''+messageWelfarefund+'\')" class="btn btn-xs btn-grey">Hủy</button>';
    $("#message_" + id + " .message-salary").html(salary);
    $("#message_" + id + " .message-welfarefund").html(welfarefund);
    $("#message_" + id + " .button_special .item_1").hide();
    $("#message_" + id + " .button_special .item_2").html(button);
  }
  function cancelEdit() {
    $("#message_" + arguments[0] + " .message-salary").html(arguments[1]);
    $("#message_" + arguments[0] + " .message-welfarefund").html(arguments[2]);
    $('#frmAdd').show();
    $("#message_" + arguments[0] + " .button_special .item_2 button").remove();
    $("#message_" + arguments[0] + " .button_special .item_1").show();
  }
  function callCrudAction(action,id) {
    $("#loaderIcon").show();
    var queryString;
    switch(action) {
      case "add":
        var number = $("#salary_basic").val();
        var price = $("#welfarefund").val();
        var queryString = {
            type:"add",
            number:number,
            price:price,
          };

        if ( (!isNaN(number) && number>0)  && (!isNaN(price) && price>0)){
          $.ajax({
          url: "{{ route('settingPackageBarCodeAjax') }}",
          data:queryString,
          type: "GET",
          success:function(data){
            $("#comment-list-box tbody.special").append(data);
            $("#loaderIcon").hide();
          },
          error:function (){}
          });
        }else{
          alert('Giá trị và Package không được để trống và phải là số nguyên dương !!!');
        }
            
      break;
      case "edit":
        var number = $("#salary_basic_"+id).val();
        var price = $("#welfarefund_"+id).val();
        var queryString = {
            type:"edit",
            id:id,
            number:number,
            price:price,
          };
        if ( (!isNaN(number) && number>0)  && (!isNaN(price) && price>0)){
          $.ajax({
          url: "{{ route('settingPackageBarCodeAjax') }}",
          data:queryString,
          type: "GET",
          success:function(hihi){
            // alert(data);
            $("#message_" + id).html(hihi);
            $('#frmAdd').show();
            $("#loaderIcon").hide();
          },
          error:function (){}
          });
        }else{
          alert('Giá trị ,Package ,Qũy phúc lợi không được để trống và phải là số nguyên dương !!!');
        }
      break;
      case "delete":
        var queryString = {
            type:"delete",
            id:id,
          };
        var r = confirm("Bạn có thực sự muốn xóa ???");
        if (r == true) {
          $.ajax({
          url: "{{ route('settingPackageBarCodeAjax') }}",
          data:queryString,
          type: "GET",
          success:function(data){
            $('#message_'+id).fadeOut();
            $("#loaderIcon").hide();
          },
          error:function (){}
          });
        } else {
           return false;
        }
      break;
    }  

  }
</script>

</div>
@endsection
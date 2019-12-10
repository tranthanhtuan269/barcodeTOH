@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row content-function">
<?php
        // echo "<pre>";
        // print_r($data);die;
?>
  <!-- Danh muc -->
  <div class="col-lg-10">
    <div class="row">
      <div class="col-lg-12">
       <div class="alert alert-success hide" role="alert"> The account is updated successfully</div>
       <div class="alert alert-danger hide" role="alert">The account is updated unsuccessfully</div>
      @if (session('flash_message_err') != '')
       <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
      @endif
      @if (session('flash_message_succ') != '')
       <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
      @endif
        <h4 class="title-fuction">Account Management</h4>
        <form class="form-horizontal" method="get" action="">
          <div class="form-group col-lg-12">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="inputName" class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{ Request::get('name') }}">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="email" class="col-sm-1 control-label">Email</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="email"  placeholder="Email" value="{{Request::get('email')}}">
                  </div>
                </div>
              </div>
            </div>
          </div>
           <div class="form-group col-lg-10">
                <div class="text-center">
                  <button type="submit" class="btn btn-default">Search</button>
                </div>
              </div>
              {{ csrf_field()}}
        </form>


      </div>
      <div class="col-lg-12">
        <h4 class="title-fuction">Account List 
            @if(in_array('user-add',$arr_route))
              <a href="{{ route('getUserAdd')}}"><i class="fa fa-plus-circle fa-lg"></i></a>
            @endif
        </h4>
        <div class="table-responsive"> 
          <table class="table table-hover">
            <tbody>
              <tr>
                <th>User Name</th>
                <th>Email </th>
                <th>Role group</th>
                <th width="15%"></th>
              </tr>
              @foreach($data as $val)
              <tr @if($val->status == 0) class="danger" @else class="success" @endif">
                <td>{{ str_limit($val->name, $limit = 30, $end = '...') }}</td> 
                <td>{{ $val->email }}</td>
                <td>{{ $val->roles_name }}</td>
                <td width="15%">
                  @if(in_array('user-edit',$arr_route))
                    <span class="view-product">
                    <a class="btn-edit" href="javascript:void(0)" onclick="showModalProduct({{ $val->id }})"><i class="glyphicon glyphicon-align-justify"></i></a>
                    <a class="btn-edit" href="{{ route('putUserEdit',['id'=>$val->id ]) }}"><i class="glyphicon glyphicon-pencil"></i></a>
                  @endif
                  @if(in_array('user-del',$arr_route))
                    @if($val->status == 1)
                    <a class="btn-edit show-user" href="javascript:void(0)" onclick="activeUser(this, {{ $val->id }}, 'unactive')"><i class="glyphicon glyphicon-minus-sign"></i></a>
                    <a class="btn-edit hide-user hide" href="javascript:void(0)" onclick="activeUser(this, {{ $val->id }}, 'active')"><i class="glyphicon glyphicon-ok-sign"></i></a>
                    @else
                    <a class="btn-edit show-user hide" href="javascript:void(0)" onclick="activeUser(this, {{ $val->id }}, 'unactive')"><i class="glyphicon glyphicon-minus-sign"></i></a>
                    <a class="btn-edit hide-user" href="javascript:void(0)" onclick="activeUser(this, {{ $val->id }}, 'active')"><i class="glyphicon glyphicon-ok-sign"></i></a>
                    @endif
                  @endif
                </td>  
              </tr>
              @endforeach
             
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-lg-12 text-right">
        {{ $data->appends(Request::all())->links() }} 
      </div>
    </div>
  </div>
</div>
<div class="modal fade product-list-modal" tabindex="-1" role="dialog" data-id="0">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Product List</h4>
      </div>
      <div class="modal-body">
        <div class="message-error danger hide">There was an error while deleting barcode!</div>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Barcode</th>
                <th>Name</th>
                <th>Manufacturer</th>
                <th>Avg Price</th>
                <th>Model</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody id="show-data">
              
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<style>
  .ajsrConfirm>div>button>span{
    display: none;
  }
</style>
@endsection
@extends('layouts_backend.master')
@section('title', 'Barcode Live')
@section('content')
<div class="row content-function">
    <!-- Danh muc -->
    <div class="col-lg-10">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="title-fuction">Role Management</h4>
                <form class="form-horizontal" method="get" action="">
                    <div class="form-group col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Roles</label>
                                    <div class="col-sm-8" style="display: inline-block;">
                                        @if(!empty($listRoles))
                                            <select name="roles_name" class="form-control">
                                                <option value="0">--All--</option>
                                                @foreach($listRoles as $val)
                                                     <option value="{{ $val['roles_name'] }}" <?php echo ($val['roles_name']==Request::get("roles_name"))?"selected":""; ?>>{{ $val['roles_name'] }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-default">Search</button>
                                </div>

                            </div>
                            
                                    
                                
                        </div>
                    </div>
                    {{ csrf_field()}}
                </form>
            </div>
            <div class="col-lg-12">
                <h4 class="title-fuction">
                    List
                    @if(in_array('roles-add',$arr_route))
                        <a href="{{ route('getRoleAdd')}}"> <i class="fa fa-plus-circle fa-lg"></i> </a>
                    @endif
                </h4>
                @if (session('flash_message_err') != '')
                <div class="alert alert-danger" role="alert"> {{ session('flash_message_err')}}</div>
                @endif
                @if (session('flash_message_succ') != '')
                <div class="alert alert-success" role="alert"> {{ session('flash_message_succ')}}</div>
                @endif
                <style type="text/css">
                    ul.menu{list-style: none;padding: 0}
                    .menu > li{float: left; margin-right: 5px;}
                </style>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Role</th>
                                <th>&nbsp;&nbsp;</th>
                            </tr>
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $role['roles_name'] }}</td>
                                <td>
                                    @if(in_array('roles-edit',$arr_route))
                                        <a class="btn-edit" href="{{ route('getRoleEdit',['id'=>$role['id']]) }}"><i class="glyphicon glyphicon-edit" style="font-size: 18px;"></i></a>
                                    @endif
                                    @if(in_array('roles-del',$arr_route))
                                        <form action="{{ url('admin/roles/del/'.$role['id']) }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <a type="submit" class="glyphicon glyphicon-remove" style="font-size: 18px;" onclick="return confirm('Bạn có chắc chắn muốn xóa ?');">
                                            </a>
                                        </form>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
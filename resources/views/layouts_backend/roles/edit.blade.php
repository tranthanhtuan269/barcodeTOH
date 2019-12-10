@extends('layouts_backend.master')
@section('title', 'Barcode Live')
@section('content')
<div class="row">
	<div class="col-lg-12">
		<h4 class="title-fuction">Edit Role</h4>
		<div class="row">
			<div class="col-lg-offset-4 col-lg-8">
			    @if (session('flash_message_err') != '')
			    <div class="alert alert-danger" role="alert">{{ session('flash_message_err')}}</div>
			    @endif
			        @if(count($errors) > 0)
			        <div class="alert alert-danger" role="alert">
			            <ul>
			                @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			                @endforeach
			            </ul>
			        </div>
			        @endif
			        <?php
			            function listCate ($data, $arr = array() , $parent = 0, $str="") {
			            	foreach ($data as $val) {
			            		$id = $val["id"];
			            		$name = $val["privilege_name"];
			            		if ($val["parent_id"] == $parent) {
			            			echo '<li class="list_data">';
			            		        if ($str == "") {
			            		        	echo'<b>'.$str.' '.$name.'</b>';
			            		        } else {
			            		        	if (in_array($id,$arr)) {
			            		        		echo $str.'<input type="checkbox" name="check_id[]" checked="checked" value="'.$id.'" >'.$name.'';
			            		        	}else{
			            		        		echo $str.'<input type="checkbox" name="check_id[]" value="'.$id.'" >'.$name.'';
			            		        	}
			            		        }
			            		    echo '</li>';
			            		    listCate ($data,$arr,$id,$str." --- ");
			            		}
			            	}
			            }
			            echo '<form action="'.url('/').'/admin/roles/edit/'.$role_id.'" method="POST">';
			            echo '<input type="hidden" name="_method" value="PUT">';
			            echo '<b>Name</b> : <input type="text" class="form_control_special" name="roles_name" style="margin-bottom:10px;" value="'.$role_name.'">';
			            echo '<ul class="list-roles list-group">';
			              listCate($data,$list_privilegs);	
			            echo '<li style="margin-top:10px;"><input type="submit" name="ok" class="btn btn-default" value="Save"></li>';
			            echo '</ul>';
			            ?>
			        {{ csrf_field()}}
			        </form>
			</div>
		</div>
    </div>
</div>
@endsection
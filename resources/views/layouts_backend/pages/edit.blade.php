@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row">
	<div class="col-lg-10" style="width: 100%;">
      <h4 class="title-fuction">Edit Page</h4>
      @include('layouts_backend.notification')

      <form class="form-horizontal" method="POST" action="{{ url('/' ) }}/admin/page/edit/{{ $data->id }}">
        {{ csrf_field()}}
        <input type="hidden" name="_method" value="PUT">
        <div class="form-group">
          <label class="col-sm-2 control-label">Title <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="title"  required="required" value="{{old('title',isset($data->title) ? $data->title : null )}}">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Content <span class="required">*</span></label>
          <div class="col-sm-8">
            <textarea rows="4" onkeydown="expandtext(this);" name="content" requried>{{ old('content',isset($data->content) ? $data->content : null ) }}</textarea>
            <script type="text/javascript">
              CKEDITOR.replace( 'content');
            </script>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-8">
            <button type="submit" class="btn btn-default">Save</button>
          </div>
        </div>
      </form>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection
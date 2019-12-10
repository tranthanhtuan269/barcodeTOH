@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row">
  <!-- Danh muc -->
	<div class="col-lg-10" style="width: 100%;">
      <h4 class="title-fuction">Create a new sub-page</h4>
      @include('layouts_backend.notification')

      <form class="form-horizontal" method="POST">
        <div class="form-group">
          <label class="col-sm-2 control-label">Title <span class="required">*</span></label>
          <div class="col-sm-8">
            <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
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
            <button type="submit" class="btn btn-default">Create</button>
          </div>
        </div>
          {{ csrf_field()}}
      </form>
	</div>
	<div class="col-lg-2"></div>
</div>
@endsection
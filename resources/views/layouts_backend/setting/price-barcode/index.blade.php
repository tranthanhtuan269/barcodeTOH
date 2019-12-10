@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row content-function">
  <div class="col-lg-10">
    <h4 class="title-fuction">1 Barcode = ${{ $data->price }}</h4>
    <div class="table-responsive">
      <form action="{{ url('/') }}/admin/setting/setSettingPriceBarCode" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">
        @if (session('flash_message_succ') != '')
          <div class="alert alert-success" role="alert"><?php echo session("flash_message_succ"); ?></div>
        @endif
        @if (session('flash_message_error') != '')
          <div class="alert alert-danger" role="alert"><?php echo session("flash_message_error"); ?></div>
        @endif
        <div class="form-group">
          <label for="exampleInputEmail1">Update new price: </label>
          <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Price ($)" value="{{ $data->price }}" name="price">
        </div>
        <button type="submit" class="btn btn-default">Update</button>
      </form>
    </div>
  </div>
</div>
@endsection
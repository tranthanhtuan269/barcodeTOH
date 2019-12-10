@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row content-function">
  <div class="col-lg-10">
      <h4 class="title-fuction">Set Barcode Free/Pay to use</h4>
      <div class="table-responsive">
        <div class="form_style">
          <div id="comment-list-box">
            <div class="form_style">
              <table class="table table-bordered table-striped">
                  <tbody>
                    <tr class="message-box text-center">
                      <td width="30%">Free/Pay to use</td>
                      @if($barCodeStatus == 1) 
                      <td colspan="2">
                        <span id="free-btn" class="btn btn-default pay-type-btn hide">Free</span>
                        <span id="charge-btn" class="btn btn-success pay-type-btn">Pay to use</span>
                      </td>
                      @else
                      <td colspan="2">
                        <span id="free-btn" class="btn btn-default pay-type-btn col-md-offset-8">Free</span>
                        <span id="charge-btn" class="btn btn-success pay-type-btn hide">Pay to use</span>
                      </td>
                      @endif
                    </tr>
                  </tbody>
              </table>
            </div>
          </div>
      </div>
    </div>

      <h4 class="title-fuction">Amount of free barcodes</h4>
      @include('layouts_backend.notification')
      <div class="table-responsive">
        <div class="form_style">
          <div id="comment-list-box">
            <div class="form_style">
                <form method="post">
                  <input type="hidden" name="_method" value="PUT">
                  {{ csrf_field()}}
                  <table class="table table-bordered table-striped">
                      <tbody>
                          <tr class="message-box text-center">
                            <td width="30%">Amount of free barcodes</td>
                            @if($barCodeStatus == 1) 
                            <td><input type="number" value="{{ $numberBarCodeFree }}" class="form-control" min="0" name="number"></td>
                            @else
                            <td><input type="number" value="{{ $numberBarCodeFree }}" class="form-control" min="0" name="number" disabled></td>
                            @endif
                            <td class="button_special">
                              <button type="submit" id="updateNumberBarcodeFreeBtn" class="btn btn-default">Save</button>
                            </td>
                          </tr>
                      </tbody>
                  </table>
                </form>
            </div>
          </div>
      </div>
  </div>
</div>
</div>
@endsection
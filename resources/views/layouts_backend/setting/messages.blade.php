@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row content-function">
  <div class="col-lg-10">
    <h4 class="title-fuction">Barcode Message List</h4>
    <div class="table-responsive table-mh-300">
      <div class="form_style">
        <div id="comment-list-box">
          <div class="form_style">
            <div id="comment-list-box">
              <?php 
                $dataBarcode = App\Models\Message::where('category', 1)->get();
                $i = 0;
                ?>
              <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">English</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody class="special">
                  @foreach($dataBarcode as $v)
                    <tr class="message-box" id="message-{{ $v->id }}">
                      <td class="message-code">{{ ++$i }}</td>
                      <td class="message-code">{{ $v->name }}</td>
                      <td class="message-english" data-content="{{ $v->message }}">{{ $v->message }}</td>
                      <td class="button-special">
                        <div class="item-1">
                          <a class="btnEditAction" name="edit" onClick="showEditMessage('{{ $v->id }}')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </div>
                        <div class="item-2 hide">
                          <a class="btnSaveAction" name="edit" onClick="saveMessage('{{ $v->id }}')"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                          <a class="btnCancelAction" name="edit" onClick="cancelEditMessage('{{ $v->id }}')"><i class="fa fa-reply" aria-hidden="true"></i></a>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <img src="{{ asset('images/general/bx_loader.gif') }}" style="display:none">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-10">
    <h4 class="title-fuction">User Message List</h4>
    <div class="table-responsive table-mh-300 table-striped">
      <div class="form_style">
        <div id="comment-list-box">
          <div class="form_style">
            <div id="comment-list-box">
              <?php 
                $dataBarcode = App\Models\Message::where('category', 2)->get();
                $i = 0;
                ?>
              <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">English</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody class="special">
                  @foreach($dataBarcode as $v)
                    <tr class="message-box" id="message-{{ $v->id }}">
                      <td class="message-code">{{ ++$i }}</td>
                      <td class="message-code">{{ $v->name }}</td>
                      <td class="message-english" data-content="{{ $v->message }}">{{ $v->message }}</td>
                      <td class="button-special">
                        <div class="item-1">
                          <a class="btnEditAction" name="edit" onClick="showEditMessage('{{ $v->id }}')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </div>
                        <div class="item-2 hide">
                          <a class="btnSaveAction" name="edit" onClick="saveMessage('{{ $v->id }}')"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                          <a class="btnCancelAction" name="edit" onClick="cancelEditMessage('{{ $v->id }}')"><i class="fa fa-reply" aria-hidden="true"></i></a>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <img src="{{ asset('images/general/bx_loader.gif') }}" style="display:none">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-10">
    <h4 class="title-fuction">Payment Message List</h4>
    <div class="table-responsive table-mh-300">
      <div class="form_style">
        <div id="comment-list-box">
          <div class="form_style">
            <div id="comment-list-box">
              <?php 
                $dataBarcode = App\Models\Message::where('category', 3)->get();
                $i = 0;
                ?>
              <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">English</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody class="special">
                  @foreach($dataBarcode as $v)
                    <tr class="message-box" id="message-{{ $v->id }}">
                      <td class="message-code">{{ ++$i }}</td>
                      <td class="message-code">{{ $v->name }}</td>
                      <td class="message-english" data-content="{{ $v->message }}">{{ $v->message }}</td>
                      <td class="button-special">
                        <div class="item-1">
                          <a class="btnEditAction" name="edit" onClick="showEditMessage('{{ $v->id }}')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </div>
                        <div class="item-2 hide">
                          <a class="btnSaveAction" name="edit" onClick="saveMessage('{{ $v->id }}')"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                          <a class="btnCancelAction" name="edit" onClick="cancelEditMessage('{{ $v->id }}')"><i class="fa fa-reply" aria-hidden="true"></i></a>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <img src="{{ asset('images/general/bx_loader.gif') }}" style="display:none">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style type="text/css" media="screen">

</style>
@endsection
@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row content-function">
  <div class="col-lg-10">
    <h4 class="title-fuction">Account function list</h4>
    <div class="table-responsive table-mh-300">
      <div class="form_style">
        <div id="comment-list-box">
          <div class="form_style">
            <div id="comment-list-box">
              <?php 
                $dataSettingEmail = App\Models\SettingEmail::where('category', 1)->get();
                $i = 0;
                ?>
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Function</th>
                    <th class="text-center">Account</th>
                    <th class="text-center">Admin</th>
                  </tr>
                </thead>
                <tbody class="special">
                  @foreach($dataSettingEmail as $v)
                    <tr class="email-box" id="email-{{ $v->id }}">
                      <td class="text-center email-code">{{ ++$i }}</td>
                      <td class="text-center email-code">{{ $v->function }}</td>
                      <td class="text-center email-code"><label><input type="checkbox" class="checkbox-user" onclick="SettingEmail(this, '{{ $v->id }}')" {{ ($v->user == 1) ? 'checked' : '' }}></label></td>
                      <td class="text-center email-code"><label><input type="checkbox" class="checkbox-admin" onclick="SettingEmail(this, '{{ $v->id }}')" {{ ($v->admin == 1) ? 'checked' : '' }}></label></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-10">
    <h4 class="title-fuction">Barcode Function List</h4>
    <div class="table-responsive table-mh-300">
      <div class="form_style">
        <div id="comment-list-box">
          <div class="form_style">
            <div id="comment-list-box">
              <?php 
                $dataSettingEmail = App\Models\SettingEmail::where('category', 2)->get();
                $i = 0;
                ?>
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Function</th>
                    <th class="text-center">Account</th>
                    <th class="text-center">Admin</th>
                  </tr>
                </thead>
                <tbody class="special">
                  @foreach($dataSettingEmail as $v)
                    <tr class="email-box" id="email-{{ $v->id }}">
                      <td class="text-center email-code">{{ ++$i }}</td>
                      <td class="text-center email-code">{{ $v->function }}</td>
                      <td class="text-center email-code"><label><input type="checkbox" class="checkbox-user" onclick="SettingEmail(this, '{{ $v->id }}')" {{ ($v->user == 1) ? 'checked' : '' }}></label></td>
                      <td class="text-center email-code"><label><input type="checkbox" class="checkbox-admin" onclick="SettingEmail(this, '{{ $v->id }}')" {{ ($v->admin == 1) ? 'checked' : '' }}></label></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-10">
    <h4 class="title-fuction">Payment Function List</h4>
    <div class="table-responsive table-mh-300">
      <div class="form_style">
        <div id="comment-list-box">
          <div class="form_style">
            <div id="comment-list-box">
              <?php 
                $dataSettingEmail = App\Models\SettingEmail::where('category', 3)->get();
                $i = 0;
                ?>
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Function</th>
                    <th class="text-center">Account</th>
                    <th class="text-center">Admin</th>
                  </tr>
                </thead>
                <tbody class="special">
                  @foreach($dataSettingEmail as $v)
                    <tr class="email-box" id="email-{{ $v->id }}">
                      <td class="text-center email-code">{{ ++$i }}</td>
                      <td class="text-center email-code">{{ $v->function }}</td>
                      <td class="text-center email-code"><label><input type="checkbox" class="checkbox-user" onclick="SettingEmail(this, '{{ $v->id }}')" {{ ($v->user == 1) ? 'checked' : '' }}></label></td>
                      <td class="text-center email-code"><label><input type="checkbox" class="checkbox-admin" onclick="SettingEmail(this, '{{ $v->id }}')" {{ ($v->admin == 1) ? 'checked' : '' }}></label></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
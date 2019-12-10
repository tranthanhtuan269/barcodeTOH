@extends('layouts_frontend.master')
@section('title', 'Barcode Live')
@section('content')
<div class="special"></div>
<div class="container">
    <div class="row">
        <div class="view-barcode account clearfix">
            <h3>View Product</h3>
            <!-- Thông báo -->
            @include('layouts_frontend.notification')
            @if(!empty($data))
            <div class="col-xs-offset-2 col-sm-8">
                <div class="item_detail row">
                    <div class="name-field">
                        <div class="item-field-label">
                            Image
                        </div>
                        <div class="item-field-data">
                            @if(strlen($data->image) > 0)
                                <?php 
                                    $list_file = explode(',', $data->image); 
                                    $check_link_http = false;

                                    if (count($list_file) == 1 && strpos($data->image, "http") !== false) {
                                    $check_link_http = true;
                                    }
                                ?>
                                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                    @if (count($list_file) > 1)
                                        <ol class="carousel-indicators">
                                            @foreach ($list_file as  $key => $image)
                                                <li data-target="#myCarousel" data-slide-to="{{ $key }}" class="@if ($key == 0) active @endif"></li>
                                            @endforeach
                                        </ol>
                                    @endif
                                    <div class="carousel-inner">
                                        @if ($check_link_http)
                                            <div class="item active">
                                                <img src="{{ $data->image }}">
                                            </div>
                                        @else
                                            @foreach ($list_file as  $key => $image)
                                                <div class="item @if ($key == 0) active @endif">
                                                    <img src="{{ asset('uploads/barcode/'.$image) }}" alt="{{ $image }}">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    @if (count($list_file) > 1)
                                        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    @endif
                                </div>
                            @else
                                <b>n/a</b>
                            @endif
                        </div>
                    </div>
                </div>
  
              <div class="item_detail row">
                  <div class="name-field">
                      <div class="item-field-label">
                          BarCode
                      </div>
                      <div class="item-field-data">
                          <b>{{ $data->barcode }}</b>
                      </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                      <div class="item-field-label">
                          Name
                      </div>
                      <div class="item-field-data">
                          <b>{{ $data->name }}</b>
                      </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                      <div class="item-field-label">
                          Model
                      </div>
                      <div class="item-field-data">
                          <b>{{ $data->model }}</b>
                      </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                      <div class="item-field-label">
                          Manufacturer
                      </div>
                      <div class="item-field-data">
                          <b>{{ $data->manufacturer }}</b>
                      </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="row">
                    <div class="name-field">
                        <div class="item-field-label">
                            Avg Price
                        </div>
                        <div class="item-field-data">
                            <b><span id="numFormatResult">{{ $data->avg_price }}</span>

                            <?php $currency_list =  config('batv_config.currency_list') ?>
                            @foreach($currency_list as $key=>$value)
                              {{ strtoupper(old('currency_unit', $data->currency_unit)) == strtoupper($key) ? $key : '' }}
                            @endforeach
                            </b>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                    <div class="item-field-label">
                      Specification
                    </div>
                    <div class="item-field-data">
                        <b class="long-data"><?php if(strlen($data->spec) > 0) echo $data->spec; else echo 'n/a'; ?></b>
                    </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                    <div class="item-field-label">
                      Feature
                    </div>
                    <div class="item-field-data">
                        <b class="long-data"><?php if(strlen($data->feature) > 0) echo $data->feature; else echo 'n/a'; ?></b>
                    </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                    <div class="item-field-label">
                      Description
                    </div>
                    <div class="item-field-data">
                        <b class="long-data"><?php if(strlen($data->description) > 0) echo $data->description; else echo 'n/a'; ?></b>
                    </div>
                  </div>
              </div>
              <div class="button text-center martop">
                  <a href="{{ url('/') }}/barcode/edit/{{ $data->id }}" class="register">Edit product</a>
                  <a href="{{ url('/') }}/barcode/list/{{ \Auth::user()->id }}" class="cancel">Back to List</a>
              </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
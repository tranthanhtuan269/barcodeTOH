@extends('layouts_backend.master')

@section('title', 'Barcode Live')

@section('content')
<div class="row">
  <div class="col-lg-10" style="width: 100%;">
      <h4 class="title-fuction">Sub-pages list
            @if(in_array('page-add',$arr_route))
              <a href="{{ route('getPageAdd')}}"> <i class="fa fa-plus-circle fa-lg"></i> </a>
            @endif
      </h4>
      @include('layouts_backend.notification')
      <div class="table-responsive"> 
        <table class="table table-hover">
          <tbody>
            <tr>
              <th>Title</th>
              <th>Action</th>
            </tr>
            {{-- <tr>
              <td>Contact Us</td>
              <td><a class="btn-edit" href="{{ route('getContactUsPage') }}"><i class="glyphicon glyphicon-edit" style="font-size: 18px"></i></a></td>
            </tr> --}}
            @foreach($data as $val)

            <tr>
              <td>{{ $val->title}}</td> 
              <td>
                @if(in_array('page-edit',$arr_route))
                  <a class="btn-edit" href="{{ route('getPageEdit',['id'=>$val->id ]) }}"><i class="glyphicon glyphicon-edit" style="font-size: 18px"></i></a>
                @endif
                @if(in_array('page-del',$arr_route))
                  <form action="{{ url('admin/page/del/'.$val->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <span class="glyphicon glyphicon-remove" style="font-size: 18px; color: #3c8dbc; cursor: pointer;">
                    </span>
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

  <script type="text/javascript">
      $(document).ready(function(){
        $('.glyphicon-remove').click(function(){
          var r = confirm("Are you sure to delete?");
          if (r == true) {
              $(this).parent().submit();
          }
        });
      });
  </script>
@endsection
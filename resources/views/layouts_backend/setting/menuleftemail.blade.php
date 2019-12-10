<div class="col-lg-2">
	<h4 class="title-fuction">Danh mục</h4>
	@if( in_array('setting-emailConfig',$arr_route) )
		<p><a href="{{route('emailConfig')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Config Send Email</a></p>
	@endif
</div>
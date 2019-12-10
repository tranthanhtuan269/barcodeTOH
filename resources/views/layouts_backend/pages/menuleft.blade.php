<div class="col-lg-2">
	<h4 class="title-fuction">Danh mục</h4>
	@if(in_array('page-list',$arr_route))
		<p><a href="{{route('getPageList')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Danh sách chuyên mục</a></p>
	@endif
</div>
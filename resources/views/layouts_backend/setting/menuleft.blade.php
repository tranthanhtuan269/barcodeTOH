<div class="col-lg-2">
	<h4 class="title-fuction">Danh mục</h4>
	@if( in_array('setting-getSettingPriceBarCode',$arr_route) )
		<p><a href="{{route('getSettingPriceBarCode')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cài đặt giá barcode</a></p>
	@endif
	@if( in_array('setting-getListSettingPackageBarCode',$arr_route) && 0)
		<p><a href="{{route('getListSettingPackageBarCode')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cài đặt gói barcode</a></p>
	@endif
	@if( in_array('setting-getSettingBarCodeFree',$arr_route) )
		<p><a href="{{route('getSettingBarCodeFree')}}" class=""><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cài đặt số lượng barcode Free</a></p>
	@endif
</div>
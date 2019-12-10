<h4 class="title-fuction">Danh mục</h4>
<p><a href="{{ route('getSearchBarCode') }}"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Tìm kiếm Barcode</a></p>
<p><a href="{{ route('listBarCodebyUser',['id'=>Auth::user()->id ]) }}"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Danh sách BarCode đang quản lý</a></p>
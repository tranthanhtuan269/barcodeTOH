<div class="item_1 clearfix">
	<div class="col-sm-3">
		<div class="text-right">
		    @if(!empty($info_account->avatar))
		    	<img src="{{ asset('uploads/users/'.$info_account->avatar) }}" alt="avatar">
    		@else
    			<img src="{{ asset('frontend/images/avatar-default_2.png') }}">
		    @endif
		</div>
	</div>
	<div class="col-sm-9">
		<h3 class="name">{{ $info_account->name }} <a href="{{ route('getAccountEdit',['id'=>$info_account->id ]) }}"><img src="{{ asset('frontend/images/edit_account.png') }}"></a></h3>
		<span>Email : <b>{{ $info_account->email }}</b></span>
<!-- 		<span>Phone : <b>{{ $info_account->phone }}</b></span>
		<span>Career : <b>{{ $info_account->career }}</b></span> -->
	</div>
</div>
@if (session('flash_message_succ') != '')
	 <div class="alert alert-success" role="alert"> {{ session('flash_message_succ') }}</div>
@endif
@if (session('flash_message_err') != '')
	 <div class="alert alert-danger" role="alert"> {{ session('flash_message_err') }}</div>
@endif
@if(count($errors) > 0)
<div class="alert alert-danger" role="alert">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if(session()->has('success'))
	<ul>
		@foreach (session()->get('success') as $msg)
			<li style="color: green; font-weight: bold;">{{ $msg }}</li>
		@endforeach
	</ul>
@endif

@if(isset($errors))
	<ul>
		@foreach ($errors as $error)
			<li style="color: red; font-weight: bold;">{{ $error }}</li>
		@endforeach
	</ul>
@endif

@if($errors->any())
	<ul>
		@foreach ($errors->all() as $error)
			<li style="color: red; font-weight: bold;">{{ $error }}</li>
		@endforeach
	</ul>
@endif
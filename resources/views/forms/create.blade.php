@extends('master')

@section('body')
	Create
	<form action="/form" method="POST">
		@csrf
		<label for="name">Name</label>
		<input type="text" name="name" id="name">

		<label for="name">Description</label>
		<input type="text" name="description" id="description">

		<button type="submit">Submit</button>
	</form>
@endsection
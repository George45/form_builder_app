@extends('master')

@section('body')
	<table>
		<tr>
			<th>Name</th>
			<th>Description</th>
		</tr>
		@foreach($forms as $form)
			<tr>
				<td>{{ $form['name'] }}</td>
				<td>{{ $form['description'] }}</td>
				<td><a href="/forms/{{ $form['id'] }}">View</a></td>
			</tr>
		@endforeach
	</table>

	<a href="/forms/create">Create Form</a>
@endsection
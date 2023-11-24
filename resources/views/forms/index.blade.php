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
				<td><a href="/form/{{ $form['id'] }}">View</a></td>
			</tr>
		@endforeach
	</table>

	<a href="/form/create">Create Form</a>
@endsection
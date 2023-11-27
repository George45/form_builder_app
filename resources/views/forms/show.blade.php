@extends('master')

@section('body')
	<h1>{{ $form['name'] }}</h1>
	<h4>{{ $form['description'] }}</h4>
	<table>
		<tr>
			<th>Field Type</th>
			<th>Name</th>
			<th>Description</th>
			<th>Config</th>
			<th>Required</th>
		</tr>
		@foreach($fields as $field)
			<tr>
				<td>{{ $field['field_type'] }}</td>
				<td>{{ $field['name'] }}</td>
				<td>{{ $field['description'] }}</td>
				<td>{{ $field['config'] }}</td>
				<td>{{ $field['required'] ? 'Yes' : 'No' }}</td>
			</tr>
		@endforeach
	</table>
	
	<a href="/forms/{{ $form['id'] }}/edit">Edit Form</a>
@endsection
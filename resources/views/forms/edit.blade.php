@extends('master')

@section('body')
	Edit
	<form action="/forms/{{ $form['id'] }}" method="POST">
		@csrf
		@method('PATCH')
		<label for="form_name">Name</label>
		<input type="text" name="name" id="form_name" value="{{ $form['name'] }}">

		<label for="form_description">Description</label>
		<input type="text" name="description" id="form_description" value="{{ $form['description'] }}">

		<button type="submit">Submit</button>
	</form>

	<br/>

	Delete
	<form action="/forms/{{ $form['id'] }}" method="POST">
		@csrf
		@method('DELETE')
		<button type="submit">Delete</button>
	</form>

	<br/>

	New Field
	<form action="/fields" method="POST">
		@csrf
		<label for="form_id">Form ID</label>
		<input type="text" name="form_id" id="form_id" value="{{ $form['id'] }}">

		<label for="field_type">Form ID</label>
		<select name="field_type" id="field_type">
			@foreach($types as $type)
				<option value="{{ $type['type'] }}" @if($type['type'] == 'text_single') selected="selected" @endif>{{ $type['name'] }}</option>
			@endforeach
		</select>

		<label for="name">Name</label>
		<input type="text" name="name" id="name">

		<label for="description">Description</label>
		<input type="text" name="description" id="description">

		<label for="config">Config</label>
		<input type="text" name="config" id="config">

		<label for="required">Required</label>
		<input type="checkbox" name="required" id="required">

		<button type="submit">Submit</button>
	</form>

	<br/>

	Edit Existing Fields
	@foreach($fields as $field)
		<form action="/fields/{{ $field['id'] }}" method="POST">
			@csrf
			@method('PATCH')
			<label for="field_{{ $field['id'] }}_form_id">Form ID</label>
			<input type="text" name="form_id" id="field_{{ $field['id'] }}_form_id" value="{{ $form['id'] }}">

			<label for="field_{{ $field['id'] }}_field_type">Form ID</label>
			<select name="field_type" id="field_{{ $field['id'] }}_field_type">
				@foreach($types as $type)
					<option value="{{ $type['type'] }}" @if($type['type'] == $field['field_type']) selected="selected" @endif>{{ $type['name'] }}</option>
				@endforeach
			</select>

			<label for="field_{{ $field['id'] }}_name">Name</label>
			<input type="text" name="name" id="field_{{ $field['id'] }}_name" value="{{ $field['name'] }}">

			<label for="field_{{ $field['id'] }}_description">Description</label>
			<input type="text" name="description" id="field_{{ $field['id'] }}_description" value="{{ $field['description'] }}">

			<label for="field_{{ $field['id'] }}_config">Config</label>
			<input type="text" name="config" id="field_{{ $field['id'] }}_config" value="{{ $field['config'] }}">

			<label for="field_{{ $field['id'] }}_required">Required</label>
			<input type="checkbox" name="required" id="field_{{ $field['id'] }}_required" @if($field['required'] === 1) checked="checked" @endif>

			<button type="submit">Submit</button>
		</form>

		<form action="/fields/{{ $field['id'] }}" method="POST">
			@csrf
			@method('DELETE')
			<input type="hidden" name="form_id" value="{{ $form['id'] }}">
			<button type="submit">Delete</button>
		</form>

		<br/>
	@endforeach
@endsection
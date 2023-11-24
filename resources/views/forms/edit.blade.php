@extends('master')

@section('body')
	Edit
	<form action="/form/{{ $form['id'] }}" method="POST">
		@csrf
		@method('PATCH')
		<label for="form_name">Name</label>
		<input type="text" name="name" id="form_name" value="{{ $form['name'] }}">

		<label for="form_description">Description</label>
		<input type="text" name="description" id="form_description" value="{{ $form['description'] }}">

		<button type="submit">Submit</button>
	</form>

	<br/>
	<br/>

	Delete Form
	<form action="/form/{{ $form['id'] }}" method="POST">
		@csrf
		@method('DELETE')
		<button type="submit">Delete</button>
	</form>

	<br/>
	<br/>

	New Field
	<form action="/field" method="POST">
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
	<br/>
	
	Edit Existing Fields
	<form action="/fields/update" method="POST">
		@csrf
		@method('PATCH')
		<label for="field_form_id">Form ID</label>
		<input type="text" name="form_id" id="field_form_id" value="{{ $form['id'] }}">

		<br/>

		@foreach($fields as $field)
			<label for="field_{{ $field['id'] }}_type">Field Type</label>
			<select name="field[{{ $field['id'] }}][field_type]" id="field_{{ $field['id'] }}_type">
				@foreach($types as $type)
					<option value="{{ $type['type'] }}"
						@if($type['type'] == $field['field_type']) selected="selected" @endif
					>{{ $type['name'] }}</option>
				@endforeach
			</select>

			<label for="field_{{ $field['id'] }}_name">Name</label>
			<input type="text"
				name="field[{{ $field['id'] }}][name]"
				id="field_{{ $field['id'] }}_name"
				value="{{ $field['name'] }}">

			<label for="field_{{ $field['id'] }}_description">Description</label>
			<input type="text"
				name="field[{{ $field['id'] }}][description]"
				id="field_{{ $field['id'] }}_description"
				value="{{ $field['description'] }}">

			<label for="field_{{ $field['id'] }}_config">Config</label>
			<input type="text"
				name="field[{{ $field['id'] }}][config]"
				id="field_{{ $field['id'] }}_config"
				value="{{ $field['config'] }}">

			<label for="field_{{ $field['id'] }}_required">Required</label>
			<input type="checkbox"
				name="field[{{ $field['id'] }}][required]"
				id="field_{{ $field['id'] }}_required"
				@if($field['required'] === 1) checked="checked" @endif>

			<button type="submit" form="fieldDelete{{ $field['id'] }}">Delete</button>

			<br/>
		@endforeach

		<button type="submit">Submit</button>
	</form>

	@foreach($fields as $field)
		<form action="/field/{{ $field['id'] }}" method="POST" id="fieldDelete{{ $field['id'] }}">
			@csrf
			@method('DELETE')
			<input type="hidden" name="form_id" id="form_id" value="{{ $form['id'] }}">
		</form>
	@endforeach

	<br/>
	<br/>

@endsection
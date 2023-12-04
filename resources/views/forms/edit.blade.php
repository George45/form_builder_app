@extends('master')

@section('body')
	Edit
	<form action="/forms/{{ $form['id'] }}" method="POST">
		@csrf
		@method('PATCH')
		<label for="form_name">Name</label>
		<input type="text"
			name="name"
			id="form_name"
			value="{{ old('form_name', $form['name']) }}">

		<label for="form_description">Description</label>
		<input type="text"
			name="description"
			id="form_description"
			value="{{ old('form_description', $form['description']) }}">

		<button type="submit">Submit</button>
	</form>

	<br/>
	<br/>

	Delete Form
	<form action="/forms/{{ $form['id'] }}" method="POST">
		@csrf
		@method('DELETE')
		<button type="submit">Delete</button>
	</form>

	<br/>
	<br/>

	New Field
	<form action="/field" method="POST">
		@csrf
		<input type="hidden"
			name="form_id"
			id="form_id"
			value="{{ $form['id'] }}">

		<label for="field_type">Field Type</label>
		<select name="field_type" id="field_type">
			@foreach($types as $type)
				<option value="{{ $type['type'] }}"
					@if($type['type'] == old('field_type', 'text_single')) selected="selected" @endif
				>{{ $type['name'] }}</option>
			@endforeach
		</select>

		<label for="name">Name</label>
		<input type="text"
			name="name"
			id="name"
			value="{{ old('name') }}">

		<label for="description">Description</label>
		<input type="text"
			name="description"
			id="description"
			value="{{ old('description') }}">

		<label for="config">Config</label>
		<input type="text"
			name="config"
			id="config"
			value="{{ old('config') }}">

		<label for="required">Required</label>
		<input type="checkbox"
			name="required"
			id="required"
			@if(old('required', 'off') === 'on') checked="checked" @endif>

		<button type="submit">Submit</button>
	</form>

	<br/>
	<br/>
	
	Edit Existing Fields
	<form action="/fields/update" method="POST">
		@csrf
		@method('PATCH')
		<input type="hidden"
			name="form_id"
			id="field_form_id"
			value="{{ $form['id'] }}">

		<br/>

		@foreach($fields as $field)
			<label for="field_{{ $field['id'] }}_type">Field Type</label>
			<select name="field[{{ $field['id'] }}][field_type]" id="field_{{ $field['id'] }}_type">
				@foreach($types as $type)
					<option value="{{ $type['type'] }}"
						@if($type['type'] == old('field.' . $field['id'] . '.field_type', $field['field_type'])) selected="selected" @endif
					>{{ $type['name'] }}</option>
				@endforeach
			</select>

			<label for="field_{{ $field['id'] }}_name">Name</label>
			<input type="text"
				name="field[{{ $field['id'] }}][name]"
				id="field_{{ $field['id'] }}_name"
				value="{{ old('field.' . $field['id'] . '.name', $field['name']) }}">

			<label for="field_{{ $field['id'] }}_description">Description</label>
			<input type="text"
				name="field[{{ $field['id'] }}][description]"
				id="field_{{ $field['id'] }}_description"
				value="{{ old('field.' . $field['id'] . '.description', $field['description']) }}">

			<label for="field_{{ $field['id'] }}_config">Config</label>
			<input type="text"
				name="field[{{ $field['id'] }}][config]"
				id="field_{{ $field['id'] }}_config"
				value="{{ old('field.' . $field['id'] . '.config', $field['config']) }}">

			<label for="field_{{ $field['id'] }}_required">Required</label>
			<input type="checkbox"
				name="field[{{ $field['id'] }}][required]"
				id="field_{{ $field['id'] }}_required"
				@if(old('field.' . $field['id'] . '.required', $field['required']) === 1) checked="checked" @endif>

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
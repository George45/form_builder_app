<?php

return [
	'delete' => [
		'error' => 'Field delete success returned false',
		'fail' => 'Failed to delete the field',
		'success' => 'Successfully deleted the field'
	],
	'store' => [
		'error' => 'Field create returned false',
		'fail' => 'Failed to create new field',
		'success' => 'Successfully created :Name'
	],
	'update' => [
		'error' => 'Field update success returned false',
		'fail' => 'Failed to update the field(s)',
		'success' => 'Successfully updated field(s)'
	],
	'validation' => [
		'max' => 'The :Field field is limited to :max characters',
		'field_invalid' => 'The request contains invalid field data',
		'field_missing' => 'The request contains incomplete field data',
		'form_invalid' => 'The request contains invalid form data',
		'form_missing' => 'The request contains incomplete form data',
		'required' => 'The :Field field is required',
	]
];
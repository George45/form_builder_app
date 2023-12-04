<?php

return [
	'delete' => [
		'error' => 'Form delete success returned false',
		'fail' => 'Failed to delete the form',
		'success' => 'Successfully deleted the form'
	],
	'edit' => [
		'fail' => 'Failed to find the requested form',
		'not_found' => 'The form you have requested does not exist'
	],
	'show' => [
		'fail' => 'Failed to find the requested form',
		'not_found' => 'The form you have requested does not exist'
	],
	'store' => [
		'error' => 'Form create returned false',
		'fail' => 'Failed to create new form',
		'success' => 'Successfully created :Name'
	],
	'update' => [
		'error' => 'Form update success returned false',
		'fail' => 'Failed to update the form',
		'success' => 'Successfully updated :Name'
	],
	'validation' => [
		'max' => 'The :Field field is limited to :max characters',
		'form_invalid' => 'The request contains invalid form data',
		'form_missing' => 'The request contains incomplete form data',
		'required' => 'The :Field field is required',
	]
];
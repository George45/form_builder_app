<?php

namespace App\Validation;

use Validator;

class FormValidator
{
	/**
	 * Validate a valid form ID
	 * 
	 * @param $id ID data to validate
	 * @return Validator
	 */
	public function validateFormId($id)
	{
		return Validator::make([
			'id' => $id
		], [
            'id' => 'integer|required',
        ], [
			'id.integer' => trans('forms.validation.form_invalid'),
			'id.required' => trans('forms.validation.form_missing'),
		]);
	}

	/**
	 * Validate form data
	 * 
	 * @param array $data Associative array that can contain the following fields: name, description
	 * @return Validator
	 */
	public function validateFormData(array $data)
	{
		return Validator::make($data, [
			'description' => 'max:1000',
			'name' => 'max:255|required'
		], [
			'description.max' => trans('forms.validation.max', [
				'field' => 'Description',
				'max' => '1000'
			]),
			'name.max' => trans('forms.validation.max', [
				'field' => 'Name',
				'max' => '255'
			]),
			'name.required' => trans('forms.validation.required', [
				'field' => 'Name'
			])
		]);
	}
}
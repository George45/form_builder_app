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
}
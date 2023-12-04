<?php

namespace App\Validation;

use Validator;

class FieldValidator
{
	/**
	 * Validate a valid field ID
	 * 
	 * @param $id ID data to validate
	 * @return Validator
	 */
	public function validateFieldId($id)
	{
		return Validator::make([
			'id' => $id
		], [
            'id' => 'integer|required',
        ], [
			'id.integer' => trans('fields.validation.field_invalid'),
			'id.required' => trans('fields.validation.field_missing')
		]);
	}

	/**
	 * Validate field data
	 * 
	 * @param array $data Associative array that can contain the following fields: form_id, field_type, name, description, config, required
	 * @param bool $newField If we are creating a new field, set to true to add "id" validation
	 * @return Validator
	 */
	public function validateFieldData(array $data, $newField = false)
	{
		return Validator::make($data, [
			'config' => 'string|nullable',
			'description' => 'max:500',
			'field_type' => 'required',
			'form_id' => 'integer|required',
			'id' => 'integer' . ($newField ? '' : '|required'),
			'name' => 'max:255|required',
			'required' => 'boolean',
		], [
			'config.string' => trans('fields.validation.form_invalid'),
			'description.max' => trans('fields.validation.max', [
				'field' => 'Description',
				'max' => '500'
			]),
			'field_type.required' => trans('fields.validation.required'),
			'form_id.integer' => trans('fields.validation.form_invalid'),
			'form_id.required' => trans('fields.validation.form_missing'),
			'id.integer' => trans('fields.validation.field_invalid'),
			'id.required' => trans('fields.validation.field_missing'),
			'name.max' => trans('fields.validation.max', [
				'field' => 'Name',
				'max' => '255'
			]),
			'name.required' => trans('fields.validation.required', [
				'field' => 'Name'
			]),
			'required.boolean' => trans('fields.validation.form_invalid')
		]);
	}
}
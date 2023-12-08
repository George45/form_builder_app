<?php

namespace App\Validation\Schema;

class FieldSchema
{
	private const DESCRIPTION_MAX = 500;
	private const NAME_MAX = 255;

	/**
	 * Array of validation rules for the field ID
	 * 
	 * @return array
	 */
	public static function idRules()
	{
		return [
            'field' => 'integer|required',
        ];
	}

	/**
	 * Array of validation messages for the field ID
	 * 
	 * @return array
	 */
	public static function idMessages()
	{
		return [
			'field.integer' => trans('fields.validation.field_invalid'),
			'field.required' => trans('fields.validation.field_missing')
		];
	}

	/**
	 * Array of validation rules for field fields
	 * 
	 * @return array
	 */
	public static function fieldRules()
	{
		return [
			'config' => 'string|nullable',
			'description' => 'max:' . self::DESCRIPTION_MAX,
			'field_type' => 'required',
			'form_id' => 'integer|required',
			'name' => 'max:' . self::NAME_MAX . '|required',
			'required' => 'boolean|required'
		];
	}

	/**
	 * Array of validation messages for field fields
	 * 
	 * @return array
	 */
	public static function fieldMessages()
	{
		return [
			'config.string' => trans('fields.validation.form_invalid'),
			'description.max' => trans('fields.validation.max', [
				'field' => 'Description',
				'max' => self::DESCRIPTION_MAX
			]),
			'field_type.required' => trans('fields.validation.required', [
				'field' => 'Type'
			]),
			'form_id.integer' => trans('fields.validation.form_invalid'),
			'form_id.required' => trans('fields.validation.form_missing'),
			'name.max' => trans('fields.validation.max', [
				'field' => 'Name',
				'max' => self::NAME_MAX
			]),
			'name.required' => trans('fields.validation.required', [
				'field' => 'Name'
			]),
			'required.boolean' => trans('fields.validation.form_invalid'),
			'required.required' => trans('fields.validation.form_invalid')
		];
	}

	/**
	 * Array of validation rules for each item of the "field" field
	 * 
	 * Takes the values from "fielidRules" and prepends "fields.*" onto each array key.
	 * 
	 * @return array
	 */
	public static function fieldsRules()
	{
		$rules = [
            'fields' => 'array|required',
        ];

		foreach(self::fieldRules() as $key => $val) {
			if($key === 'form_id') {
				continue;
			}

			$rules['fields.*.' . $key] = $val;
		}

		return $rules;
	}

	/**
	 * Array of validation messages for each item of the "field" field.  
	 * 
	 * Takes the values from "fieldMessages" and prepends "fields.*" onto each array key.
	 * 
	 * @return array
	 */
	public static function fieldsMessages()
	{
		$messages = [
			'fields.array' => trans('fields.validation.form_invalid'),
			'fields.required' => trans('fields.validation.form_missing')
		];

		foreach(self::fieldMessages() as $key => $val) {
			if($key === 'form_id') {
				continue;
			}

			$messages['fields.*.' . $key] = $val;
		}

		return $messages;
	}
}
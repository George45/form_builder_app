<?php

namespace App\Validation\Schema;

class FormSchema
{
	private const DESCRIPTION_MAX = 255;
	private const NAME_MAX = 255;

	/**
	 * Array of validation rules for the form ID
	 * 
	 * @return array
	 */
	public static function idRules()
	{
		return [
            'form' => 'integer|required'
        ];
	}
	
	/**
	 * Array of validation messages for the form ID
	 * 
	 * @return array
	 */
	public static function idMessages()
	{
		return [
			'form.integer' => trans('forms.validation.form_invalid'),
			'form.required' => trans('forms.validation.form_missing')
		];
	}

	/**
	 * Array of validation rules for the form fields
	 * 
	 * @return array
	 */
	public static function fieldRules()
	{
		return [
			'description' => 'max:' . self::DESCRIPTION_MAX,
			'name' => 'max:' . self::NAME_MAX . '|required'
		];
	}

	/**
	 * Array of validation messages for the form ID
	 * 
	 * @return array
	 */
	public static function fieldMessages()
	{
		return [
			'description.max' => trans('forms.validation.max', [
				'field' => 'Description',
				'max' => self::DESCRIPTION_MAX
			]),
			'name.max' => trans('forms.validation.max', [
				'field' => 'Name',
				'max' => self::NAME_MAX
			]),
			'name.required' => trans('forms.validation.required', [
				'field' => 'Name'
			])
		];
	}
}
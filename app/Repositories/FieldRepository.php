<?php

namespace App\Repositories;

use App\Models\FormField;

class FieldRepository
{
	/**
	 * Return all fields by their form ID
	 * 
	 * @param int $id ID of the form to retrieve fields for
	 * @return array<FormField>
	 */
	public function getById(int $id)
	{
		return FormField::select([
			'id',
			'field_type',
			'name',
			'description',
			'config',
			'required'
		])->where('form_id', $id)->get()->toArray();
	}
}
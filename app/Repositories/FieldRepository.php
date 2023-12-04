<?php

namespace App\Repositories;

use App\Models\FormField;
use Exception;
use Illuminate\Support\Facades\DB;

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

	/**
	 * Create a new field and return the created field
	 * 
	 * @param array $data Associative array that can contain the following fields: form_id, field_type, name, description, config, required
	 * @return FormField
	 */
	public function createField($data)
	{
		return FormField::create($data);
	}

	/**
	 * Update a field and then return if the update has been successful
	 * 
	 * @param array $data Associative array that can contain the following fields: field_type, name, description, config, required
	 * @return bool
	 */
	public function updateField($id, $data)
	{
		return FormField::where('id', $id)->update($data);
	}

	/**
	 * Update multiple fields and then return if the update has been successful
	 * 
	 * @param array $data Key value array where the key is the field ID and value is an associative array that can contain the following fields: field_type, name, description, config, required
	 * @return bool
	 */
	public function updateFields($data)
	{
		try {
			DB::transaction(function() use($data) {
				foreach($data as $id => $field) {
					FormField::where('id', $id)->update($field);
				}
			});

			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * Delete a field
	 * 
	 * @param int $id ID of the field to delete
	 * @return bool
	 */
	public function deleteField($id)
	{
		return FormField::where('id', $id)->delete();
	}
}
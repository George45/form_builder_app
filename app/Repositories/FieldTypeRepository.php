<?php

namespace App\Repositories;

use App\Models\FieldType;

class FieldTypeRepository
{
	/**
	 * Return all field types
	 * 
	 * @return array<FieldTypes>
	 */
	public function getAll()
	{
		return FieldType::select([
			'name',
			'type',
			'template'
		])->orderBy('name', 'asc')->get()->toArray();
	}
}
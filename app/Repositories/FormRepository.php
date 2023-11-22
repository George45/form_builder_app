<?php

namespace App\Repositories;

use App\Models\Form;

class FormRepository
{
	/**
	 * Return all forms
	 * 
	 * @return array<Form>
	 */
	public function getAll()
	{
		return Form::select([
			'id',
			'name',
			'description'
		])->get()->toArray();
	}

	/**
	 * Return a form with a specific ID
	 * 
	 * @param int $id ID of the form to retrieve
	 * @return array
	 */
	public function getById(int $id)
	{
		return Form::select([
			'id', // todo: not used
			'name',
			'description'
		])->where('id', $id)->get()->toArray();
	}

	/**
	 * Create a new form and then return the created form
	 * 
	 * @param array $data Associative array that can contain the following fields: name, description
	 * @return Form
	 */
	public function createForm(array $data)
	{
		return Form::create($data);
	}
}
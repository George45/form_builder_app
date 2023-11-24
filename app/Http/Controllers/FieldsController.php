<?php

namespace App\Http\Controllers;

use App\Repositories\FieldRepository;
use Illuminate\Http\Request;

class FieldsController
{
	public FieldRepository $field;

	public function __construct()
	{
		$this->field = new FieldRepository();
	}

	/**
	 * Create a new field
	 * 
	 * @param Request $request
	 */
	public function store(Request $request)
	{
		$data = $request->only([
			'form_id',
			'field_type',
			'name',
			'description',
			'config'
		]);
		$data['required'] = $request->boolean('required');

		$field = $this->field->createField($data);

		return redirect()->action([FormsController::class, 'edit'], ['form' => $data['form_id']]);
	}

	/**
	 * Update a field
	 * 
	 * @todo: currently unused, consider removing
	 * @param Request $request
	 * @param int $id The ID of the form to update
	 */
	// public function update(Request $request, int $id)
	// {
	// 	$data = $request->only([
	// 		'field_type',
	// 		'name',
	// 		'description',
	// 		'config'
	// 	]);
	// 	$data['required'] = $request->boolean('required');

	// 	$success = $this->field->updateField($id, $data);

	// 	return redirect()->action([FormsController::class, 'edit'], ['form' => $request->get('form_id')]);
	// }

	/**
	 * Update multiple fields
	 * 
	 * @param Request $request
	 */
	public function updateMultiple(Request $request)
	{
		$data = $request->all();
		foreach($data['field'] as $key => $value) {
			$data['field'][$key]['required'] = array_key_exists('required', $data['field'][$key]);
		}

		$success = $this->field->updateFields($data['field']);

		return redirect()->action([FormsController::class, 'edit'], ['form' => $request->get('form_id')]);
	}

	/**
	 * Delete a field
	 * 
	 * @param Request $request
	 * @param int $id The ID of the field to delete
	 */
	public function destroy(Request $request, int $id)
	{
		$success = $this->field->deleteField($id);

		return redirect()->action([FormsController::class, 'edit'], ['form' => $request->get('form_id')]);
	}
}

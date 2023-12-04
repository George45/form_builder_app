<?php

namespace App\Http\Controllers;

use App\Exceptions\FieldDeleteFailed;
use App\Exceptions\FieldStoreFailed;
use App\Exceptions\FieldUpdateFailed;
use App\Repositories\FieldRepository;
use App\Validation\FieldValidator;
use App\Validation\FormValidator;
use Illuminate\Http\Request;
use Log;

class FieldsController
{
	public FieldRepository $field;
	public FieldValidator $validator;

	public function __construct()
	{
		$this->field = new FieldRepository();
		$this->validator = new FieldValidator();
	}

	/**
	 * Create a new field
	 * 
	 * @param \Illuminate\Http\Request $request
	 * 
	 * @throws FieldStoreFailed
	 */
	public function store(Request $request)
	{
		$data = $request->only([
			'config',
			'description',
			'field_type',
			'form_id',
			'name'
		]);
		$data['required'] = $request->boolean('required');
		$validator = $this->validator->validateFieldData($data, true);

		if($validator->fails()) {
			return back()
				->with([
					'errors' => $validator->errors()->all()
				])
				->withInput($request->only([
					'config',
					'description',
					'field_type',
					'form_id',
					'name',
					'required'
				]));
		}

		try {
			$field = $this->field->createField($data);
			if (!$field) {
				throw new FieldStoreFailed(trans('fields.store.error'));
			}
		} catch (\Exception $e) {
			Log::error('Failed to create field: ' . $e->getMessage());

			return redirect()
				->action([FormsController::class, 'edit'], ['form' => $data['form_id']])
				->with([
					'errors' => [trans('fields.store.fail')]
				])
				->withInput($request->only([
					'config',
					'description',
					'field_type',
					'form_id',
					'name',
					'required'
				]));
		}

		return redirect()
			->action([FormsController::class, 'edit'], ['form' => $data['form_id']])
			->with([
				'success' => [trans('fields.store.success', ['name' => $data['name']])]
			]);
	}

	/**
	 * Update multiple fields
	 * 
	 * @param \Illuminate\Http\Request $request
	 * 
	 * @throws FieldUpdateFailed
	 */
	public function update(Request $request)
	{
		$formId = $request->get('form_id', '');
		$formValidator = new FormValidator();
		$formValidator = $formValidator->validateFormId($formId);

		if($formValidator->fails()) {
			return redirect()
				->action([FormsController::class, 'edit'], ['form' => $formId])
				->with([
					'errors' => $formValidator->errors()->all()
				])
				->withInput($request->only(['field']));
		}

		$data = $request->only(['field']);
		foreach($data['field'] as $key => $value) {
			$data['field'][$key]['id'] = $key;
			$data['field'][$key]['required'] = array_key_exists('required', $data['field'][$key]);
		}

		foreach($data['field'] as $key => $value) {
			$validator = $this->validator->validateFieldData([
				...$value,
				'form_id' => $formId,
				'id' => $key
			]);

			if($validator->fails()) {
				return redirect()
					->action([FormsController::class, 'edit'], ['form' => $formId])
					->with([
						'errors' => $validator->errors()->all()
					])->withInput($request->only(['field']));
			}
		}

		try {
			$success = $this->field->updateFields($data['field']);
			if (!$success) {
				throw new FieldUpdateFailed(trans('fields.update.error'));
			}
		} catch (\Exception $e) {
			Log::error('Failed to update fields: ' . $e->getMessage());

			return redirect()
				->action([FormsController::class, 'edit'], ['form' => $formId])
				->with([
					'errors' => [trans('fields.update.fail')]
				]);
		}

		return redirect()
			->action([FormsController::class, 'edit'], ['form' => $formId])
			->with([
				'success' => [trans('fields.update.success')]
			]);
	}

	/**
	 * Delete a field
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @param int $id The ID of the field to delete
	 * 
	 * @throws FieldDeleteFailed
	 */
	public function destroy(Request $request, int $id)
	{
		$formId = $request->get('form_id', '');
		$validator = $this->validator->validateFieldId($id);

		if($validator->fails()) {
			return redirect()
				->action([FormsController::class, 'edit'], ['form' => $formId])
				->with([
					'errors' => $validator->errors()->all()
				]);
		}

		try {
			$success = $this->field->deleteField($id);
			if (!$success) {
				throw new FieldDeleteFailed(trans('fields.delete.error'));
			}
		} catch (\Exception $e) {
			Log::error('Failed to delete field: ' . $e->getMessage());

			return redirect()
				->action([FormsController::class, 'edit'], ['form' => $formId])
				->with([
					'errors' => [trans('fields.delete.fail')]
				]);
		}

		return redirect()
			->action([FormsController::class, 'edit'], ['form' => $formId])
			->with([
				'success' => [trans('fields.delete.success')]
			]);
	}
}

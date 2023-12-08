<?php

namespace App\Http\Controllers;

use App\Exceptions\FieldDeleteFailed;
use App\Exceptions\FieldStoreFailed;
use App\Exceptions\FieldUpdateFailed;
use App\Http\Requests\FieldDeleteRequest;
use App\Http\Requests\FieldStoreRequest;
use App\Http\Requests\FieldUpdateRequest;
use App\Repositories\FieldRepository;
use Log;

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
	 * @param \Illuminate\Http\FieldStoreRequest $request
	 * 
	 * @throws FieldStoreFailed
	 */
	public function store(FieldStoreRequest $request)
	{
		$data = $request->safe([
			'config',
			'description',
			'field_type',
			'form_id',
			'name',
			'required'
		]);

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
	 * @param \Illuminate\Http\FieldUpdateRequest $request
	 * 
	 * @throws FieldUpdateFailed
	 */
	public function update(FieldUpdateRequest $request)
	{
		$formId = $request->safe(['form'])['form'];
		$data = $request->safe(['fields']);

		try {
			$success = $this->field->updateFields($data['fields']);
			if (!$success) {
				throw new FieldUpdateFailed(trans('fields.update.error'));
			}
		} catch (\Exception $e) {
			Log::error('Failed to update fields: ' . $e->getMessage());

			return redirect()
				->action([FormsController::class, 'edit'], ['form' => $formId])
				->with([
					'errors' => [trans('fields.update.fail')]
				])
				->withInput($request->only(['fields']));
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
	 * @param \App\Http\Requests\FieldDeleteRequest $request
	 * 
	 * @throws FieldDeleteFailed
	 */
	public function destroy(FieldDeleteRequest $request)
	{
		$fieldId = $request->safe(['field'])['field'];
		$formId = $request->safe(['form'])['form'];

		try {
			$success = $this->field->deleteField($fieldId);
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

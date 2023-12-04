<?php

namespace App\Http\Controllers;

use App\Exceptions\FieldDeleteFailed;
use App\Exceptions\FieldStoreFailed;
use App\Exceptions\FieldUpdateFailed;
use App\Repositories\FieldRepository;
use Illuminate\Http\Request;
use Log;
use Validator;

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
	 * @param Illuminate\Http\Request $request
	 * 
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
		$validator = Validator::make($data, [
			'config' => 'string',
			'description' => 'max:500',
			'field_type' => 'required',
			'form_id' => 'integer|required',
			'name' => 'max:255|required',
			'required' => 'boolean',
		], [
			'config.string' => trans('fields.validation.form_invalid'),
			'description.max' => trans('fields.validation.max', ['field' => 'Description', 'max' => '500']),
			'field_type.required' => trans('fields.validation.required'),
			'form_id.integer' => trans('fields.validation.form_invalid'),
			'form_id.required' => trans('fields.validation.form_missing'),
			'name.max' => trans('fields.validation.max', ['field' => 'Name', 'max' => '255']),
			'name.required' => trans('fields.validation.required', ['field' => 'Name']),
			'required.boolean' => trans('fields.validation.form_invalid')
		]);

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
	 * @param Illuminate\Http\Request $request
	 * 
	 * @throws FieldUpdateFailed
	 */
	public function update(Request $request)
	{
		$formId = $request->get('form_id', '');
		$validator = Validator::make(['form' => $formId], [
			'form' =>  'integer|required'
        ], [
			'form.integer' => trans('fields.validation.form_invalid'),
			'form.required' => trans('fields.validation.form_missing')
		]);

		if($validator->fails()) {
			return redirect()
				->action([$this::class, 'edit'], ['form' => $formId])
				->with([
					'errors' => $validator->errors()->all()
				])
				->withInput($request->only(['field']));
		}

		$data = $request->only(['field']);
		foreach($data['field'] as $key => $value) {
			$data['field'][$key]['required'] = array_key_exists('required', $data['field'][$key]);
		}

		$rules = [
			'config' => 'string',
			'description' => 'max:500',
			'field_type' => 'required',
			'id' => 'integer|required',
			'name' => 'max:255|required',
			'required' => 'boolean'
		];
		$messages = [
			'config.string' => trans('fields.validation.field_invalid'),
			'description.max' => trans('fields.validation.max', ['field' => 'Description', 'max' => '500']),
			'field_type.required' => trans('fields.validation.required'),
			'id.integer' => trans('fields.validation.field_invalid'),
			'id.required' => trans('fields.validation.field_missing'),
			'name.max' => trans('fields.validation.max', ['field' => 'Name', 'max' => '255']),
			'name.required' => trans('fields.validation.required', ['field' => 'Name']),
			'required.boolean' => trans('fields.validation.field_invalid')
		];

		foreach($data['field'] as $key => $value) {
			$validator = Validator::make([
				...$value,
				'id' => $key
			], $rules, $messages);

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
	 * @param Illuminate\Http\Request $request
	 * @param int $id The ID of the field to delete
	 * 
	 * @throws FieldDeleteFailed
	 */
	public function destroy(Request $request, int $id)
	{
		$formId = $request->get('form_id', '');
		$validator = Validator::make([
			...$request->route()->parameters(),
			'form' => $formId
		], [
            'field' => 'integer|required',
			'form' =>  'integer|required'
        ], [
			'field.integer' => trans('fields.validation.field_invalid'),
			'field.required' => trans('fields.validation.field_missing'),
			'form.integer' => trans('fields.validation.form_invalid'),
			'form.required' => trans('fields.validation.form_missing')
		]);

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

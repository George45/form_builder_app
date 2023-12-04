<?php

namespace App\Http\Controllers;

use App\Repositories\FieldRepository;
use App\Repositories\FieldTypeRepository;
use App\Repositories\FormRepository;
use App\Exceptions\FormDeleteFailed;
use App\Exceptions\FormStoreFailed;
use App\Exceptions\FormUpdateFailed;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;
use Validator;

class FormsController
{
	public FieldRepository $field;
	public FormRepository $form;
	public FieldTypeRepository $types;

	public function __construct()
	{
		$this->field = new FieldRepository();
		$this->form = new FormRepository();
		$this->types = new FieldTypeRepository();
	}

	/**
	 * Return the view for listing all created forms
	 */
	public function index()
	{
		return view('forms.index', [
			'forms' => $this->form->getAll()
		]);
	}

	/**
	 * Return the view for creating a new form
	 */
	public function create()
	{
		return view('forms.create');
	}

	/**
	 * Create a new form
	 * 
	 * @param Illuminate\Http\Request $request
	 * 
	 * @throws FormStoreFailed
	 */
	public function store(Request $request)
	{
		$data = $request->only(['name', 'description']);
		$validator = Validator::make($data, [
			'description' => 'max:1000',
			'name' => 'max:255|required'
		], [
			'description.max' => trans('forms.validation.max', ['field' => 'Description', 'max' => '255']),
			'name.max' => trans('forms.validation.max', ['field' => 'Name', 'max' => '255']),
			'name.required' => trans('forms.validation.required', ['field' => 'Name'])
		]);

		if($validator->fails()) {
			return back()
				->with([
					'errors' => $validator->errors()->all()
				])
				->withInput($data);
		}

		try {
			$form = $this->form->createForm($data);
			if (!$form) {
				throw new FormStoreFailed(trans('forms.store.error'));
			}
		} catch (\Exception $e) {
			Log::error('Failed to create form: ' . $e->getMessage());

			return redirect()
				->action([$this::class, 'create'])
				->with([
					'errors' => [trans('forms.store.fail')]
				])
				->withInput($data);
		}

		return redirect()
			->action([$this::class, 'show'], ['form' => $form->id])
			->with([
				'success' => [trans('forms.store.success', ['name' => $data['name']])]
			]);
	}

	/**
	 * Return the view for displaying info about a form
	 * 
	 * @param int $id The ID of the form to view
	 */
	public function show(Request $request, $id)
	{
		$validator = Validator::make($request->route()->parameters(), [
            'form' => 'integer|required',
        ], [
			'form.integer' => trans('forms.validation.form_invalid'),
			'form.required' => trans('forms.validation.form_missing'),
		]);

		if($validator->fails()) {
			return redirect()
				->action([$this::class, 'index'])
				->with([
					'errors' => $validator->errors()->all()
				]);
		}

		try {
			$form = $this->form->getById($id);
		} catch (ModelNotFoundException $e) {
			return redirect()
				->action([$this::class, 'index'])
				->with([
					'errors' => [trans('forms.show.not_found')]
				]);
		} catch (\Exception $e) {
			return redirect()
				->action([$this::class, 'index'])
				->with([
					'errors' => [trans('forms.show.fail')]
				]);
		}

		return view('forms.show', [
			'form' => $form,
			'fields' => $this->field->getById($id)
		]);
	}

	/**
	 * Return the view for editing a form
	 * 
	 * @param int $id The ID of the form to edit
	 */
	public function edit(Request $request, $id)
	{
		$validator = Validator::make($request->route()->parameters(), [
            'form' => 'integer|required',
        ], [
			'form.integer' => trans('forms.validation.form_invalid'),
			'form.required' => trans('forms.validation.form_missing'),
		]);

		if($validator->fails()) {
			return redirect()
				->action([$this::class, 'index'])
				->with([
					'errors' => $validator->errors()->all()
				]);
		}

		try {
			$form = $this->form->getById($id);
		} catch (ModelNotFoundException $e) {
			return redirect()
				->action([$this::class, 'index'])
				->with([
					'errors' => [trans('forms.edit.not_found')]
				]);
		} catch (\Exception $e) {
			return redirect()
				->action([$this::class, 'index'])
				->with([
					'errors' => [trans('forms.edit.fail')]
				]);
		}

		return view('forms.edit', [
			'form' => $form,
			'fields' => $this->field->getById($id),
			'types' => $this->types->getAll()
		]);
	}

	/**
	 * Update a form
	 * 
	 * @param Illuminate\Http\Request $request
	 * @param int $id The ID of the form to update
	 * 
	 * @throws FormUpdateFailed
	 */
	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->route()->parameters(), [
            'form' => 'integer|required',
        ], [
			'form.integer' => trans('forms.validation.form_invalid'),
			'form.required' => trans('forms.validation.form_missing'),
		]);

		if($validator->fails()) {
			return redirect()
				->action([$this::class, 'edit'], ['form' => $id])
				->with([
					'errors' => $validator->errors()->all()
				])
				->withInput([
					'form_name' => $request->get('name'),
					'form_description' => $request->get('description', '')
				]);
		}

		$data = $request->only(['name', 'description']);
		$validator = Validator::make($data, [
			'description' => 'max:1000',
			'name' => 'max:255|required'
		], [
			'description.max' => trans('forms.validation.max', ['field' => 'Description', 'max' => '1000']),
			'name.max' => trans('forms.validation.max', ['field' => 'Name', 'max' => '255']),
			'name.required' => trans('forms.validation.required', ['field' => 'Name'])
		]);

		if($validator->fails()) {
			return redirect()
				->action([$this::class, 'edit'], ['form' => $id])
				->with([
					'errors' => $validator->errors()->all()
				])
				->withInput([
					'form_name' => $data['name'],
					'form_description' => $request->get('description', '')
				]);
		}

		try {
			$success = $this->form->updateForm($id, $data);
			if (!$success) {
				throw new FormUpdateFailed(trans('forms.update.error'));
			}
		} catch (\Exception $e) {
			Log::error('Failed to update form: ' . $e->getMessage());

			return redirect()
				->action([$this::class, 'edit'], ['form' => $id])
				->with([
					'errors' => [trans('forms.update.fail')]
				])
				->withInput([
					'form_name' => $data['name'],
					'form_description' => $request->get('description', '')
				]);
		}

		return redirect()
			->action([$this::class, 'edit'], ['form' => $id])
			->with([
				'success' => [trans('forms.update.success', ['name' => $data['name']])]
			]);
	}

	/**
	 * Delete a form
	 * 
	 * @param Illuminate\Http\Request $request
	 * @param int $id The ID of the form to delete
	 * 
	 * @throws FormDeleteFailed
	 */
	public function destroy(Request $request, $id)
	{
		$validator = Validator::make($request->route()->parameters(), [
            'form' => 'integer|required',
        ], [
			'form.integer' => trans('forms.validation.form_invalid'),
			'form.required' => trans('forms.validation.form_missing'),
		]);

		if($validator->fails()) {
			return redirect()
				->action([$this::class, 'edit'], ['form' => $id])
				->with([
					'errors' => $validator->errors()->all()
				]);
		}

		try {
			$success = $this->form->deleteForm($id);
			if (!$success) {
				throw new FormDeleteFailed(trans('forms.delete.error'));
			}
		} catch (\Exception $e) {
			Log::error('Failed to delete form: ' . $e->getMessage());

			return redirect()
				->action([$this::class, 'edit'], ['form' => $id])
				->with([
					'errors' => [trans('forms.delete.fail')]
				]);
		}

		return redirect()
			->action([$this::class, 'index'])
			->with([
				'success' => [trans('forms.delete.success')]
			]);
	}
}

<?php

namespace App\Http\Controllers;

use App\Exceptions\FormDeleteFailed;
use App\Exceptions\FormStoreFailed;
use App\Exceptions\FormUpdateFailed;
use App\Http\Requests\FormStoreRequest;
use App\Http\Requests\FormGetRequest;
use App\Http\Requests\FormUpdateRequest;
use App\Repositories\FieldRepository;
use App\Repositories\FieldTypeRepository;
use App\Repositories\FormRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;

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
	 * @param \App\Http\Requests\FormStoreRequest $request
	 * 
	 * @throws FormStoreFailed
	 */
	public function store(FormStoreRequest $request)
	{
		$data = $request->safe(['name', 'description']);

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
	 * @param \App\Http\Requests\FormGetRequest $request
	 */
	public function show(FormGetRequest $request)
	{
		$formId = $request->safe(['form'])['form'];

		try {
			$form = $this->form->getById($formId);
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
			'fields' => $this->field->getById($formId)
		]);
	}

	/**
	 * Return the view for editing a form
	 * 
	 * @param \App\Http\Requests\FormGetRequest $request
	 */
	public function edit(FormGetRequest $request)
	{
		$formId = $request->safe(['form'])['form'];

		try {
			$form = $this->form->getById($formId);
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
			'fields' => $this->field->getById($formId),
			'types' => $this->types->getAll()
		]);
	}

	/**
	 * Update a form
	 * 
	 * @param \App\Http\Requests\FormUpdateRequest $request
	 * 
	 * @throws FormUpdateFailed
	 */
	public function update(FormUpdateRequest $request)
	{
		$formId = $request->safe(['form'])['form'];
		$data = $request->safe(['name', 'description']);

		try {
			$success = $this->form->updateForm($formId, $data);
			if (!$success) {
				throw new FormUpdateFailed(trans('forms.update.error'));
			}
		} catch (\Exception $e) {
			Log::error('Failed to update form: ' . $e->getMessage());

			return redirect()
				->action([$this::class, 'edit'], ['form' => $formId])
				->with([
					'errors' => [trans('forms.update.fail')]
				])
				->withInput([
					'form_name' => $data['name'],
					'form_description' => $request->get('description', '')
				]);
		}

		return redirect()
			->action([$this::class, 'edit'], ['form' => $formId])
			->with([
				'success' => [trans('forms.update.success', ['name' => $data['name']])]
			]);
	}

	/**
	 * Delete a form
	 * 
	 * @param \App\Http\Requests\FormGetRequest $request
	 * 
	 * @throws FormDeleteFailed
	 */
	public function destroy(FormGetRequest $request)
	{
		$formId = $request->safe(['form'])['form'];

		try {
			$success = $this->form->deleteForm($formId);
			if (!$success) {
				throw new FormDeleteFailed(trans('forms.delete.error'));
			}
		} catch (\Exception $e) {
			Log::error('Failed to delete form: ' . $e->getMessage());

			return redirect()
				->action([$this::class, 'edit'], ['form' => $formId])
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

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
		$data = $request->only(['name', 'description']);

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
	 * @param int $id The ID of the form to view
	 */
	public function show(FormGetRequest $request, $id)
	{
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
	 * @param \App\Http\Requests\FormGetRequest $request
	 * @param int $id The ID of the form to edit
	 */
	public function edit(FormGetRequest $request, $id)
	{
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
	 * @param \App\Http\Requests\FormUpdateRequest $request
	 * @param int $id The ID of the form to update
	 * 
	 * @throws FormUpdateFailed
	 */
	public function update(FormUpdateRequest $request, $id)
	{
		$data = $request->only(['name', 'description']);

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
	 * @param \App\Http\Requests\FormGetRequest $request
	 * @param int $id The ID of the form to delete
	 * 
	 * @throws FormDeleteFailed
	 */
	public function destroy(FormGetRequest $request, $id)
	{
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

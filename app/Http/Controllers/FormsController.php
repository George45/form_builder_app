<?php

namespace App\Http\Controllers;

use App\Repositories\FieldRepository;
use App\Repositories\FieldTypeRepository;
use App\Repositories\FormRepository;
use Illuminate\Http\Request;

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
	 * @param Request $request
	 */
	public function store(Request $request)
	{
		$data = $request->only(['name', 'description']);

		$form = $this->form->createForm($data);

		return redirect()->action([$this::class, 'show'], ['form' => $form->id]);
	}

	/**
	 * Return the view for displaying info about a form
	 * 
	 * @param int $id The ID of the form to view
	 */
	public function show(int $id)
	{
		return view('forms.show', [
			'form' => $this->form->getById($id)[0],
			'fields' => $this->field->getById($id)
		]);
	}

	/**
	 * Return the view for editing a form
	 * 
	 * @param int $id The ID of the form to edit
	 */
	public function edit(int $id)
	{
		return view('forms.edit', [
			'form' => $this->form->getById($id)[0],
			'fields' => $this->field->getById($id),
			'types' => $this->types->getAll()
		]);
	}

	/**
	 * Update a form
	 * 
	 * @param Request $request
	 * @param int $id The ID of the form to update
	 */
	public function update(Request $request, int $id)
	{
		$data = $request->only(['name', 'description']);

		$success = $this->form->updateForm($id, $data);

		return redirect()->action([$this::class, 'show'], ['form' => $id]);
	}

	/**
	 * Delete a form
	 * 
	 * @param Request $request
	 * @param int $id The ID of the form to delete
	 */
	public function destroy(int $id)
	{
		$success = $this->form->deleteForm($id);

		return redirect()->action([$this::class, 'index']);
	}
}

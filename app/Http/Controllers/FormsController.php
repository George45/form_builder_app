<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormsController
{
	/**
	 * Return the view for listing all created forms
	 */
	public function index()
	{
		return view('forms.index');
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
		dd('store');
	}

	/**
	 * Return the view for displaying info about a form
	 * 
	 * @param integer $id The ID of the form to view
	 */
	public function show(int $id)
	{
		return view('forms.show', [
			'id' => $id
		]);
	}

	/**
	 * Return the view for editing a form
	 * 
	 * @param integer $id The ID of the form to edit
	 */
	public function edit(int $id)
	{
		return view('forms.edit', [
			'id' => $id
		]);
	}

	/**
	 * Update a form
	 * 
	 * @param Request $request
	 * @param integer $id The ID of the form to update
	 */
	public function update(Request $request, int $id)
	{
		dd('update');
	}

	/**
	 * Delete a form
	 * 
	 * @param Request $request
	 * @param integer $id The ID of the form to delete
	 */
	public function destroy(int $id)
	{
		dd('destroy');
	}
}

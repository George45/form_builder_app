<?php

namespace App\Http\Requests;

use App\Validation\Schema\FieldSchema;
use Illuminate\Foundation\Http\FormRequest;

class FieldStoreRequest extends FormRequest
{
	/**
     * Retrives the field request parameter for validation
     *
     * @return void
     */
    protected function prepareForValidation() {
		$this->merge([
            'required' => $this->request->get('required', 'off') === 'on',
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
		return FieldSchema::fieldRules();
    }

	/**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return FieldSchema::fieldMessages();
    }
}

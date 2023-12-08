<?php

namespace App\Http\Requests;

use App\Validation\Schema\FieldSchema;
use App\Validation\Schema\FormSchema;
use Illuminate\Foundation\Http\FormRequest;

class FieldDeleteRequest extends FormRequest
{
	/**
     * Retrives the field request parameter for validation
     *
     * @return void
     */
    protected function prepareForValidation() {
		$this->merge([
            'field' => $this->route('field'),
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
        return [
			...FormSchema::idRules(),
			...FieldSchema::idRules()
		];
    }

	/**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
			...FormSchema::idRules(),
			...FieldSchema::idMessages()
		];
    }
}

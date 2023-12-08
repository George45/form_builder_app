<?php

namespace App\Http\Requests;

use App\Validation\Schema\FieldSchema;
use App\Validation\Schema\FormSchema;
use Illuminate\Foundation\Http\FormRequest;

class FieldUpdateRequest extends FormRequest
{
	/**
     * Retrives the field request parameter for validation
     *
     * @return void
     */
    protected function prepareForValidation() {
		$data = $this->toArray();
		foreach($data['fields'] as $key => $val) {
			$data['fields'][$key]['required'] = isset($data['fields'][$key]['required'])
				? $data['fields'][$key]['required'] === 'on'
				: false;
		}
        $this->merge($data);
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
			...FieldSchema::fieldsRules()
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
			...FormSchema::idMessages(),
			...FieldSchema::fieldsMessages()
		];
    }
}

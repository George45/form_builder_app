<?php

namespace App\Http\Requests;

use App\Validation\Schema\FormSchema;
use Illuminate\Foundation\Http\FormRequest;

class FormStoreRequest extends FormRequest
{
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
		return FormSchema::fieldRules();
    }

	/**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return FormSchema::fieldMessages();
    }
}

<?php

namespace App\Http\Requests\Email;

use Illuminate\Foundation\Http\FormRequest;

class EmailAddressStore extends FormRequest
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
        $rules = [
            'email' => 'required|email|unique:email_addresses,email,' . $this->email_id,
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:255',
            'emailable_id' => 'required|integer',
            'emailable_type' => 'required|string',
            'total_space' => 'required|numeric|min:0.0',
            'space_unit' => 'required|string|in:MB,GB', // Ensure space unit is one of the allowed values
        ];

        return $rules;
    }
}

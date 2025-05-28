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
            'type' => 'required|string',
            'is_active' => 'boolean',
            'is_primary' => 'boolean',
            'description' => 'required|string|max:255',
            'emailable_id' => 'required|integer',
            'emailable_type' => 'required|string',
        ];

        return $rules;
    }
}

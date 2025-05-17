<?php

namespace App\Http\Requests\Submission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SecurityStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Changed to true to allow authorized users to make the request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'type' => ['required', Rule::in(['bid_security', 'performance_guarantee'])],
            'bid_security_type' => [
                'nullable',
                Rule::in(['pay_order_cdr', 'bank_guarantee']),
                Rule::requiredIf(function () {
                    return $this->input('type') === 'bid_security';
                })
            ],
            'favor_of' => 'required|string|max:255',
            'date_issued' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:date_issued',
            'amount' => 'required|numeric|min:1000',
            'project_name' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'reference_number' => 'nullable|string|max:255',
            'status' => ['nullable', Rule::in(['active', 'expired', 'released'])],
            'client_id' => 'nullable|exists:clients,id',
            'submitted_by' => 'nullable|exists:partners,id',
            'bank_id' => 'nullable|exists:banks,id',
        ];
        if (request()->has('security_id')) {
            $rules += ['document' => "nullable|file|max:1024|mimes:pdf"];
        } else {
            $rules += ['document' => "required|file|max:1024|mimes:pdf"];
        }

        return $rules;
    }
    protected function prepareForValidation()
    {
        if ($this->has('amount')) {
            $this->merge([
                'amount' => str_replace(',', '', $this->input('amount')),
            ]);
        }
    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'bid_security_type.required_if' => 'The bid security type field is required when type is bid_security.',
            'expiry_date.after_or_equal' => 'The expiry date must be after or equal to the issued date.',
        ];
    }
}

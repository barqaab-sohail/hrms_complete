<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class AsDisposalStore extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sold_date' => 'required|date',
            'sold_price' => 'nullable|numeric|min:0',
            'reason' => 'required|string|max:500',
            'sold_to' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'sold_date.required' => 'Sold date is required',
            'sold_date.date' => 'Sold date must be a valid date',
            'reason.required' => 'Reason for disposal is required',
            'sold_price.numeric' => 'Sold price must be a number',
            'sold_price.min' => 'Sold price cannot be negative'
        ];
    }
}
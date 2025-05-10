<?php

namespace App\Http\Requests\Self;

use Illuminate\Foundation\Http\FormRequest;

class PersonalDocumentStore extends FormRequest
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
        $sizeInMB = 3000;

        if ($this->size) {
            $sizeInMB = (int) $this->size / 1024;
        }

        $rules = [
            'description' => 'required',
        ];

        if (request()->has('document')) {
            $rules += ['document' => "required|file|max:$sizeInMB|mimes:doc,docx,xls,xlsx,jpeg,jpg,png,pdf",];
        }

        return $rules;
    }

    public function messages()
    {

        return [
            'document.mimes' => ' Only doc, docx, xls, xlsx, jpeg, jpg, png, pdf type attachment allowed',
        ];
    }
}

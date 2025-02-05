<?php

namespace App\Http\Requests\Folder;

use Illuminate\Foundation\Http\FormRequest;

class FolderDocumentStore extends FormRequest
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
            'description' => 'required|max:190',
        ];

        //If method is POST then document is required otherwise in Patch method document is nullable.
        if (!$this->folder_document_id) {
            $rules += ['document' => 'required|file|max:4000|mimes:jpg,png,jpeg,pdf'];
        } else {
            $rules += ['document' => 'nullable|file|max:4000|mimes:jpg,png,jpeg,pdf'];
        }

        return $rules;

    }
}

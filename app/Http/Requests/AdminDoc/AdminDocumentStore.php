<?php

namespace App\Http\Requests\AdminDoc;

use Illuminate\Foundation\Http\FormRequest;

class AdminDocumentStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [

            'reference_no' => 'required',
            'document_date' => 'required',
            'description' => 'required|max:190',
        ];
        if ($this->document_id) {
            $rules += ['document' => 'nullable|file|max:1000|mimes:jpg,png,jpeg,pdf,doc,docx'];
        } else {
            $rules += ['document' => 'required|file|max:1000|mimes:jpg,png,jpeg,pdf,doc,docx'];
        }


        return $rules;
    }
}

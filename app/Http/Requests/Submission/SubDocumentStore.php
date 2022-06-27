<?php

namespace App\Http\Requests\Submission;

use Illuminate\Foundation\Http\FormRequest;

class SubDocumentStore extends FormRequest
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
        'description'=> 'required|max:190',
        
        ];

         //If method is POST then document is required otherwise in Patch method document is nullable.
            if (!$this->sub_document_id) {
                $rules += [ 'document'=>'required|file|max:4000|mimes:jpg,png,jpeg,pdf,doc,docx'];
            }else{
                 $rules += [ 'document'=>'nullable|file|max:4000|mimes:jpg,png,jpeg,pdf,doc,docx'];
            }

    
        return $rules;
    }
}

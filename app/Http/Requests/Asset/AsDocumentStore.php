<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class AsDocumentStore extends FormRequest
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
            if (!$this->as_document_id) {
                $rules += [ 'document'=>'required|file|max:1000|mimes:jpg,png,jpeg,pdf'];
            }else{
                 $rules += [ 'document'=>'nullable|file|max:1000|mimes:jpg,png,jpeg,pdf'];
            }

    
        return $rules;
    }

    


}

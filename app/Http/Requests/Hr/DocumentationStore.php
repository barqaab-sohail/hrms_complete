<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class DocumentationStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
     public function __construct(\Illuminate\Http\Request $request)
    {
    $request->request->add(['hr_employee_id' => session('hr_employee_id')]);
    $description =  $request->description;
    }

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
        

        return [
            'document'=>'required|file|max:2000|mimes:pdf,jpg,jpeg,tif,png',
            'description'=>'not_in:picture,Picture,PICTURE,Appointment Letter,Cnic Back,Cnic Front, Hr Form',
            'hr_document_name_id' => 'required|unique_with:hr_document_name_hr_documentation,hr_employee_id',
             
        ];
    }

      public function messages()
    {
        
        $descriptionDetail = $this->description;
        return [
            'document.mimes' => 'only pdf, jpg, tif and png attachment allowed',
            'hr_document_name_id.unique_with' => 'this document names is already entered',
            'description.not_in' => $descriptionDetail.' is reserved word, please use alternate word in document description',


        ];
    }

      
}

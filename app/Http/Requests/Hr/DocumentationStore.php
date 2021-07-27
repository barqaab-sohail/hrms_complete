<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Hr\HrDocumentName;

class DocumentationStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    private $documentNames;

    public function __construct(\Illuminate\Http\Request $request)
    {
        
        $this->documentNames =  HrDocumentName::all()->pluck('name')->toArray();
        $this->documentNames=implode(',',$this->documentNames);


        $request->request->add(['hr_employee_id' => session('hr_employee_id')]);
    
        if($request->hr_document_name_id !='Other'){
            $documentName =  HrDocumentName::find($request->hr_document_name_id);

             if ($documentName->name == 'Picture'){
            $request->request->add(['mime_type' => ',jpeg,jpg,png']);
            }else{
                $request->request->add(['mime_type' => 'jpeg,jpg,png,pdf']);
            }
        }
        else{
            $request->request->add(['mime_type' => 'jpeg,jpg,png,pdf']);
        }
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
            'document'=>'required|file|max:4000|mimes:'.$this->mime_type,
            'description'=>'not_in:'.$this->documentNames,
            //'description'=>'not_in:picture,Picture,PICTURE,Appointment Letter,Cnic Back,Cnic Front, Hr Form',
            'hr_document_name_id' => 'required|unique_with:hr_document_name_hr_documentation,hr_employee_id',
            'document_date' => 'required|date',
             
        ];
    }

    public function messages()
    {
        
        return [
            'document.mimes' => ' picture only jpg,png allowed otherwise pdf, jpg, tif and png attachment allowed',
            'hr_document_name_id.unique_with' => 'this document names is already entered',
            'description.not_in' => $this->description.' is reserved word, please use alternate word in document description',


        ];
    }

      
}

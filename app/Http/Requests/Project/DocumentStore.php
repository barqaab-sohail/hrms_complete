<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project\PrDocumentName;

class DocumentStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    private $documentNames;
    private $description;
    
    public function __construct(\Illuminate\Http\Request $request)
    {
        
        // $this->documentNames =  PrDocumentName::all()->pluck('name')->toArray();
        // $this->documentNames=implode(',',$this->documentNames);
        // $this->description = $request->description;
       
        // if($request->pr_document_name_id !='Other'){
        //     $documentName =  PrDocumentName::find($request->pr_document_name_id);

        //      if ($documentName->name == 'Picture'){
        //     $request->request->add(['mime_type' => ',jpeg,jpg,png']);
        //     }else{
        //         $request->request->add(['mime_type' => 'jpeg,jpg,png,pdf']);
        //     }
        // }
        // else{
        //     $request->request->add(['mime_type' => 'jpeg,jpg,png,pdf']);
        // }
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
            'document'=>'required|file|max:30000|mimes:doc,docx,jpeg,jpg,png,pdf',
            'description'=>'required',
            //'description'=>'not_in:picture,Picture,PICTURE,Appointment Letter,Cnic Back,Cnic Front, Hr Form',
            //'pr_document_name_id' => 'required|unique_with:pr_document_pr_document_name,pr_detail_id',
             
        ];
    }

      public function messages()
    {
        
        return [
            'document.mimes' => ' picture only jpg,png allowed otherwise pdf, jpg, tif and png attachment allowed',
           


        ];
    }

      
}

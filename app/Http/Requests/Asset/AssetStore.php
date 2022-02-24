<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class AssetStore extends FormRequest
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
        'as_class_id'=> 'required',
        'as_sub_class_id'=> 'required',
        'description'=> 'required|max:190',
       
        
        ];

         //If method is POST then document is required otherwise in Patch method document is nullable.
            if ($this->getMethod() == 'POST') {
                $rules += [ 'document'=>'required|file|max:1000|mimes:pdf,jpg,png,jpeg'];
            }else{
                 $rules += [ 'document'=>'nullable|file|max:1000|mimes:pdf,jpg,png,jpeg'];
            }

    
        return $rules;
    }

    public function messages()
    {
        return [
            'as_class_id.required' => 'class field is required',
            'as_sub_class_id.required' => 'sub class field is required',
            'description.required' => 'description  field is required',
            'purchase_date.required' => 'purchase date is rquired',  
            'as_purchase_condition_id.required' => 'purchase condition is rquired',  
            'client_id.required' => 'ownership is required',  
            'asset_location.required' => 'asset location is rquired', 
            'hr_employee_id.required_if' => 'employee field is required',
            'office_id.required_ifl' => 'office field is required',
            'as_class_id.required' => 'class field is required',
            'as_condition_type_id.required' => 'asset condition field is required',           
            
        ];
    }
}

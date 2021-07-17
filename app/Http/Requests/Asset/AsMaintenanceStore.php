<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class AsMaintenanceStore extends FormRequest
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
        'maintenance_detail'=> 'required',
        'maintenance_cost'=> 'required',
        'maintenance_date'=> 'required',
        ];

        return $rules;
    }
}

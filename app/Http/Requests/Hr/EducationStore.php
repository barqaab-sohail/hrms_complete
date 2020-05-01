<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class EducationStore extends FormRequest
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
        return [
           'role' => [
                'required', 
                Rule::unique('role_user', 'role_id')->where(function ($query) {
                    return $query->where('user_id', $this->user_id); 
            // assuming you're sending 'user_id' in the request
                }),
            ]
        ];
    }
}

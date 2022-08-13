<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project\PrStaff;

class PrStaffStore extends FormRequest
{


    public function getValidatorInstance()
    {
        $this->cleanDate();
        return parent::getValidatorInstance();
    }

    protected function cleanDate()
    {
        if ($this->from) {
            $this->merge([
                'from' => \Carbon\Carbon::parse($this->from)->format('Y-m-d')
            ]);
        }
        if ($this->to) {
            $this->merge([
                'to' => \Carbon\Carbon::parse($this->to)->format('Y-m-d')
            ]);
        }

        //check employee enter in already ender dates.

        $data = PrStaff::where('hr_employee_id', $this->hr_employee_id)
            ->whereDate('from', '<=', $this->from)
            ->whereDate('to', '>=', $this->from)
            ->first();
        //if found than request "from date" set equal to database enter "from date".
        //so that unique validation fail.
        if ($data) {
            $this->merge([
                'from' => $data->from
            ]);
        }
    }




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

            'position' => 'required',
            'from' => 'required|date|unique:pr_staffs,from,' . $this->staff_id,
            'to' => 'nullable|date|after_or_equal:from|required_if:status,Input Ended|prohibited_if:status,Working',
            'status' => 'required'

        ];

        if ($this->status == 'Working') {
            $rules += ['hr_employee_id' => 'required|unique_with:pr_staffs,status,' . $this->staff_id];
        } else {
            $rules += ['hr_employee_id' => 'required'];
        }


        return $rules;
    }

    public function messages()
    {

        return [
            'to.prohibited_if' => "To Date  must be empty, if Staff is Working",
            'hr_employee_id.unique_with' => 'This Employee is already Entered',
            'from.unique' => 'These dates Employee is already Entered',
        ];
    }
}

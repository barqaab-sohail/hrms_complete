<?php

namespace App\Imports;

use App\Models\Project\PrDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProjectsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new PrDetail([
            'name'  => $row['name'],
            'contract_type_id'  => $row['contract_type_id'],
            'client_id'   => $row['client_id'],
            'commencement_date'    => $row['commencement_date'],
            'contractual_completion_date'    => $row['contractual_completion_date'],
            'actual_completion_date'  => $row['actual_completion_date'],  //nullable
            'pr_status_id'   => $row['pr_status_id'],
            'pr_role_id'   => $row['pr_role_id'],
            'share'   => $row['share'], //nullable
            'project_no'   => $row['project_no'], //nullable
            
        ]);
    }


    public function rules(): array
    {
        return [
            'name'=>'required|max:190|unique:pr_details,name',
            'client_id'=>'required|numeric',
            'commencement_date'=>'required|date',
            'contractual_completion_date'=>'nullable|date|after:commencement_date',
            'actual_completion_date'=>'nullable|date', 
            'pr_status_id'=>'required|numeric',
            'pr_role_id'=>'required|numeric',
            'contract_type_id'=>'required|numeric',
            'project_no'=>'required|max:6',
            'share'=>'required',
        ];
    }



}

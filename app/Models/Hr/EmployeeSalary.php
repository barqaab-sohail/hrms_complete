<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeSalary extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'hr_salary_id', 'effective_date'];
    protected $appends = ['gross_salary'];


    function getGrossSalaryAttribute()
    {
        
        $totalSalary = $this->hrSalary->total_salary;
        $allowancesSum = $this->hrAllowances->sum('amount');
        return $totalSalary + $allowancesSum;
    }


    public function hrSalary()
    {
     
        return $this->belongsTo('App\Models\Hr\HrSalary');
    }

    
    public function hrAllowances()
    {
        return $this->hasMany('App\Models\Hr\HrAllowance');
    }


}
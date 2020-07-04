<?php

namespace App\Models\Self;

use Illuminate\Database\Eloquent\Model;

class SsTask extends Model
{
    


    protected $fillable = ['task_detail', 'target_completion','completion_date','status','remarks','hr_employee_id'];

    //default value of status=0
    protected $attributes = [
        'status' => 0 //0 pending and 1 completed 
    ];
    
    //get value of status and show in string as per statusOptions
    public function getStatusAttribute($attribute)
    {
        return $this->statusOptions()[$attribute];
    }
    
    public function statusOptions()
    {
        return [
            0 => 'Pending',
            1 => 'Completed'
        ];
    }
    



}

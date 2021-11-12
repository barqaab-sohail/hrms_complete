<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrRight extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_detail_id','hr_employee_id','progress','invoice','payment'];

    
    // //default value of 1, which is "No Access"
    // protected $attributes = [
    //     'progress' => 1,
    //     'invoice' => 1,
    // ];
    
    // //get value of invoice and show in string as per statusOptions
    // public function getInvoiceAttribute($attribute)
    // {
    //     return $this->statusOptions()[$attribute];
    // }

    // //get value of invoice and show in string as per statusOptions
    // public function getProgressAttribute($attribute)
    // {
    //     return $this->statusOptions()[$attribute];
    // }
    
    // public function statusOptions()
    // {
    //     return [
    //         1 => 'No Access',
    //         2 => 'View Record',
    //         3 => 'Edit Record',
    //         4 => 'Delete'

    //     ];
    // }
}

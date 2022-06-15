<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubDescription extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['submission_id', 'sub_status_id','sub_financial_type_id','sub_cv_format_id','sub_evaluation_type_id','technical_opening_date','financial_opening_date','total_marks','passing_marks','technical_weightage','financial_weightage','scope_of_services','scope_of_work'];

}

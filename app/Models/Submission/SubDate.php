<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubDate extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;
    
   protected $fillable = ['submission_id', 'submission_date','submission_time','address'];
}

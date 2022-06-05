<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubStatusType extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
   protected $fillable = ['submission_id','sub_status_id'];
}

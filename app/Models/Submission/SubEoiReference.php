<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubEoiReference extends Model implements Auditable
{
  
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['submission_id','eoi_reference_id'];
}

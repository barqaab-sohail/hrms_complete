<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubAddress extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;
   
   protected $fillable = ['submission_id', 'address','designation'];
}

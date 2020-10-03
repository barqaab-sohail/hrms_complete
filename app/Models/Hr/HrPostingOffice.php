<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPostingOffice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_posting_id','office_id'];

   
}

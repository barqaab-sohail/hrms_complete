<?php

namespace App\Models\Project\Progress;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrProgressActivity extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['pr_detail_id','name','weightage'];
}

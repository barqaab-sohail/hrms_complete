<?php

namespace App\Models\Project\Progress;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class PrIssue extends Model implements Auditable
{
    use HasFactory;
     use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_detail_id','description','responsibility', 'status','resolve_date'];


}

<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrPartner extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_detail_id','pr_role_id','partner_id','share'];
}

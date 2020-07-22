<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
 

class PrPartnerContact extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_partner_id','authorize_person','designation','mobile','landline','email'];
}

<?php

namespace App\Models\Project\Contractor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrContractor extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_detail_id', 'contractor_name', 'contract_name', 'contract_signing_date', 'effective_date', 'completion_period', 'contractual_completion_date'];
}

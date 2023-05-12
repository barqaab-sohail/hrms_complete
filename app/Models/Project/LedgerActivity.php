<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LedgerActivity extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_detail_id', 'voucher_date', 'voucher_no', 'reference_date', 'reference_no', 'description', 'debit', 'credit', 'balance', 'remarks'];
}

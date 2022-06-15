<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubMultiCurrency extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['sub_competitor_id', 'currency_id','conversion_rate','conversion_date','currency_price'];
}

<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Common\Partner;

class SubParticipateRole extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = ['submission_id','partner_id','pr_role_id','share'];

    // protected $appends = ['partner_id','pr_role_id'];
    
    // function getPartnerIdAttribute() {
    //    $partnerName = Partner::find($this->partner_id);
    //     return $partnerName->name;
    // }


}

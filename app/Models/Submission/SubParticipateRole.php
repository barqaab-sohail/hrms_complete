<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Common\Partner;
use App\Models\Project\PrRole;

class SubParticipateRole extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = ['submission_id','partner_id','pr_role_id','share'];

    protected $appends = ['partner_name','role_name'];
    
    function getPartnerNameAttribute() {
        return Partner::find($this->partner_id)->name;
    }
    function getRoleNameAttribute() {
        return PrRole::find($this->pr_role_id)->name;
    }

    public function subCost(){

        return $this->hasOne('App\Models\Submission\SubCost');

    }


    // public function getPartnerIdAttribute($value) {
    //     return Partner::find($value)->name;
    // }

    // public function getPrRoleIdAttribute($value) {
    //     return PrRole::find($value)->name;
    // }




}

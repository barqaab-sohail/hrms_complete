<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDetail extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;
    

   protected $fillable = ['name', 'client_id','commencement_date','contractual_completion_date','actual_completion_date','pr_status_id','pr_role_id','contract_type_id','pr_division_id','project_no','share'];


   public function client(){
        return $this->belongsTo('App\Models\Common\Client');
    }

    public function prRole(){
        return $this->belongsTo('App\Models\Project\PrRole');
    }

    public function getFormattedCommencementDateAttribute() {
  		return \Carbon\Carbon::parse($this->commencement_date)->format('M d, Y');
	}

}

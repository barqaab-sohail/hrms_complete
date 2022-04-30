<?php

namespace App\Models\Project\Progress;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrProgressActivity extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['pr_detail_id','level','name','weightage','belong_to_activity'];

   	protected $appends = ['belong_to_activity_name'];
    
    function getBelongToActivityNameAttribute() {     
        return  PrProgressActivity::find($this->belong_to_activity)->name??'';
    }
    
}

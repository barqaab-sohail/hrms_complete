<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrDocumentation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id','description','document_date','file_name','size','path','extension','content','pr_document_id'];



    public function hrDocumentName()
    {
        return $this->belongsToMany('App\Models\Hr\HrDocumentName');
        
    }
}

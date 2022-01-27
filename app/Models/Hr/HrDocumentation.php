<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrDocumentation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id','description','document_date','file_name','size','path','extension','content'];

    

    public function hrDocumentationProject()
    {
        return $this->hasOne('App\Models\Hr\HrDocumentationProject');
        
    }

    public function hrDocumentName()
    {
        return $this->belongsToMany('App\Models\Hr\HrDocumentName');
        
    }

    public function hrEmployee(){
        return $this->belongsTo('App\Models\Hr\HrEmployee');
    }

    
}

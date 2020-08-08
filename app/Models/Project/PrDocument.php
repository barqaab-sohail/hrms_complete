<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDocument extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $auditExclude = [
        'content',
    ];


    protected $fillable = ['pr_detail_id','reference_no','description','document_date','file_name','size','path','extension','pr_folder_name_id'];


    public function prDocumentContent(){
        return $this->hasOne('App\Models\Project\PrDocumentContent');
    }
   
}

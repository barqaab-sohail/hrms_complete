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


    protected $fillable = ['pr_detail_id','description','document_date','file_name','size','path','extension','content'];



    public function prDocumentName()
    {
        return $this->belongsToMany('App\Models\Project\PrDocumentName');
        
    }
}

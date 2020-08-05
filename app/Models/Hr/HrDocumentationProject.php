<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrDocumentationProject extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_documentation_id','pr_document_id'];

}

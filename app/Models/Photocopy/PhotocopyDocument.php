<?php

namespace App\Models\Photocopy;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PhotocopyDocument extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['photcopy_id', 'description', 'file_name', 'extension', 'reference_no', 'document_date', 'path', 'size'];
}

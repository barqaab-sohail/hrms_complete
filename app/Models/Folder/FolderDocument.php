<?php

namespace App\Models\Folder;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class FolderDocument extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['folder_id', 'description', 'file_name', 'extension', 'reference_no', 'document_date', 'path', 'size'];
}

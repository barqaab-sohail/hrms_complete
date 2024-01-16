<?php

namespace App\Models\AdminDoc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AdminDocument extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = ['reference_no', 'description', 'document_date', 'file_name', 'size', 'path', 'extension'];
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TempUploadFile extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table ='temp_upload_file'; 
    protected $fillable = ['file_name', 'extension','path','size'];

    protected $appends = ['full_path'];

    function getFullPathAttribute()
    {
        return url('/storage/' . $this->path . $this->file_name);
    }
}
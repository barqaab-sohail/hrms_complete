<?php

namespace App\Models\Self;

use Illuminate\Database\Eloquent\Model;

class PersonalDocument extends Model
{
    protected $fillable = ['user_id', 'description', 'document_date', 'file_name', 'extension', 'path', 'size'];

    protected $appends = ['full_path'];

    function getFullPathAttribute()
    {
        return url('/storage/' . $this->path . $this->file_name);
    }
}

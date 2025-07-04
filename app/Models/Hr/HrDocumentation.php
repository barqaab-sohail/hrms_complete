<?php

namespace App\Models\Hr;

use App\Models\Common\ShortUrl;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrDocumentation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'description', 'document_date', 'file_name', 'size', 'path', 'extension', 'content'];

    protected $appends = ['full_path', 'tiny_url'];

    public function getTinyUrlAttribute()
    {
        $shortUrl = ShortUrl::firstOrCreate(
            ['original_url' => $this->full_path],
            ['short_code' => ShortUrl::generateUniqueShortCode()]
        );
        return url('/document/' . $shortUrl->short_code); // Updated this line
    }

    function getFullPathAttribute()
    {
        return url('/storage/' . $this->path . $this->file_name);
    }

    public function hrDocumentationProject()
    {
        return $this->hasOne('App\Models\Hr\HrDocumentationProject');
    }

    public function hrDocumentName()
    {
        return $this->belongsToMany('App\Models\Hr\HrDocumentName');
    }

    public function hrEmployee()
    {
        return $this->belongsTo('App\Models\Hr\HrEmployee');
    }
}

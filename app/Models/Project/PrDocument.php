<?php

namespace App\Models\Project;

use App\Models\Common\ShortUrl;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDocument extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $auditExclude = [
        'content',
    ];


    protected $fillable = ['pr_detail_id', 'reference_no', 'description', 'document_date', 'file_name', 'size', 'path', 'extension', 'pr_folder_name_id'];

    protected $appends = ['full_path', 'tiny_url'];

    // Cache duration in minutes
    const SHORT_URL_CACHE_DURATION = 1440; // 24 hours

    function getFullPathAttribute()
    {
        return url('/storage/' . $this->path . $this->file_name);
    }

    public function getTinyUrlAttribute()
    {
        $cacheKey = 'short_url_' . md5($this->full_path);

        return Cache::remember($cacheKey, self::SHORT_URL_CACHE_DURATION, function () {
            $shortUrl = ShortUrl::firstOrCreate(
                ['original_url' => $this->full_path],
                ['short_code' => ShortUrl::generateUniqueShortCode()]
            );
            return url('/document/' . $shortUrl->short_code);
        });
    }

    function getSizeAttribute($value)
    {
        return (round(($value / (1024*1024)), 2));
    }

    public function prDocumentContent()
    {
        return $this->hasOne('App\Models\Project\PrDocumentContent');
    }

    public function prFolderName()
    {
        return $this->belongsTo('App\Models\Project\PrFolderName', 'pr_folder_name_id');
    }

    public function prDetail()
    {
        return $this->belongsTo('App\Models\Project\PrDetail');
    }
}

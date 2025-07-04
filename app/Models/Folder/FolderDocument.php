<?php

namespace App\Models\Folder;

use App\Models\Common\ShortUrl;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class FolderDocument extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['folder_id', 'description', 'file_name', 'extension', 'reference_no', 'document_date', 'path', 'size'];

    protected $appends = ['full_path', 'tiny_url'];

    function getFullPathAttribute()
    {
        return url('/storage/' . $this->path . $this->file_name);
    }

    public function getTinyUrlAttribute()
    {
        $shortUrl = ShortUrl::firstOrCreate(
            ['original_url' => $this->full_path],
            ['short_code' => ShortUrl::generateUniqueShortCode()]
        );
        return url('/document/' . $shortUrl->short_code); // Updated this line
    }
}

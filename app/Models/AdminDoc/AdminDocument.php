<?php

namespace App\Models\AdminDoc;

use App\Models\Common\ShortUrl;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminDocument extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = ['reference_no', 'description', 'document_date', 'file_name', 'size', 'path', 'extension'];

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

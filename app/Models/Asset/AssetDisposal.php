<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class AssetDisposal extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'asset_id',
        'sold_date',
        'sold_price',
        'reason',
        'sold_to',
        'notes'
    ];

    protected $casts = [
        'sold_date' => 'date',
        'sold_price' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}

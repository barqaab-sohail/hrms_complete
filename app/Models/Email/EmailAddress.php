<?php

namespace App\Models\Email;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'email',
        'type',
        'is_active',
        'is_primary',
        'description',
        'emailable_id',
        'emailable_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_primary' => 'boolean'
    ];

    /**
     * Get the parent emailable model (employee or project).
     */
    public function emailable()
    {
        return $this->morphTo();
    }
}

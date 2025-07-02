<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_url',
        'short_code',
        'click_count'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate a unique short code if not provided
            if (empty($model->short_code)) {
                $model->short_code = self::generateUniqueShortCode();
            }
        });
    }

    public static function generateUniqueShortCode($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (self::where('short_code', $code)->exists());

        return $code;
    }
}

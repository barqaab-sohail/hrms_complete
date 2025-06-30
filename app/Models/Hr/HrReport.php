<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'route',
        'description',
        'is_active',
        'order'
    ];
}

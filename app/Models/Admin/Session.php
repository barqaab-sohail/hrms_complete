<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    
    protected $fillable = ['user_id'];
    public $timestamps = false;
}

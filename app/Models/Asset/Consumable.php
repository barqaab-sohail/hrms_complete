<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Consumable extends Model implements Auditable
{
    use HasFactory;
   use \OwenIt\Auditing\Auditable;
    protected $fillable = ['name'];
}

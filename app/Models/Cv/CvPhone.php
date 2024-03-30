<?php

namespace App\Models\Cv;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CvPhone extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'phone', 'cv_contact_id',
    ];

    public function cvDetail()
    {
        return $this->belongsTo('App\Models\Cv\CvDetail');
    }
}

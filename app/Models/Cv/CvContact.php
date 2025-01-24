<?php

namespace App\Models\Cv;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CvContact extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'address','city_id', 'state_id','country_id','email', 'cv_detail_id',
    ];

	public function cvDetail()
    {
        return $this->belongsTo('App\Models\Cv\CvDetail');
    }
}

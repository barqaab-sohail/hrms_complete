<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubCv extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable; 
  use HasFactory; 

	protected $fillable = ['sub_position_id','file_name','size','path','extension','content'];
}

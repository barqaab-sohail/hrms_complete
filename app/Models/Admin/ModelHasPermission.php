<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ModelHasPermission extends Model
{
    


    public function user(){

        return $this->belongsTo('App\User','model_id');
    }


    public function permission(){

        return $this->belongsTo('App\Models\Admin\Permission','permission_id');
    }

}

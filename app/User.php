<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Hr\HrDocumentation;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements Auditable
{
    use Notifiable, HasRoles, HasApiTokens;
     use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','user_status','session_id','last_login_at','last_login_ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hrEmployee(){
        return $this->hasOne('App\Models\Hr\HrEmployee');
    }
 
    public function picturePath(){
        $picture = HrDocumentation::where([['description','Picture'],['hr_employee_id',auth()->user()->hrEmployee->id??'']])->first();
        if($picture){
        $picturePath = $picture->path.$picture->file_name;
        }else{
            $picturePath='';
        }
        return $picturePath;
    }
}

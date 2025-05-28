<?php

namespace App\Models\Email;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\Relation;

class EmailAddress extends Model
{

    protected static function boot()
    {
        parent::boot();

        // Define morph map directly in the model
        Relation::morphMap([
            'employee' => \App\Models\Hr\HrEmployee::class,
            'project' => \App\Models\Project\PrDetail::class, // Make sure this class exists
            'department' => \App\Models\Hr\HrDepartment::class, // Example for department
        ]);
    }
    // use SoftDeletes;

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

    // app/Models/EmailAddress.php

    // 
    /**
     * Get the parent emailable model (employee or project).
     */
    public function emailable()
    {
        return $this->morphTo();
    }
}

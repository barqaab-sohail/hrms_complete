<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MisUser extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['user_id', 'is_allow_mis', 'hr_employee_id', 'is_allow_management_access'];

    /**
     * Check if a user is allowed MIS access
     *
     * @param int $userId
     * @return bool
     */
    public static function isAllowMis($userId)
    {
        $misUser = static::where('user_id', $userId)->first();
        return $misUser ? $misUser->is_allow_mis : false;
    }

    /**
     * Check if a user is allowed Management access
     *
     * @param int $userId
     * @return bool
     */
    public static function isAllowManagement($userId)
    {
        $misUser = static::where('user_id', $userId)->first();
        return $misUser ? $misUser->is_allow_management_access : false;
    }

    /**
     * Get MIS user record for a user with fallback
     *
     * @param int $userId
     * @return MisUser
     */
    public static function getForUser($userId)
    {
        return static::firstOrCreate(
            ['user_id' => $userId],
            [
                'is_allow_mis' => false,
                'is_allow_management_access' => false
            ]
        );
    }

    /**
     * Scope to get users with MIS access
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithMisAccess($query)
    {
        return $query->where('is_allow_mis', true);
    }

    /**
     * Scope to get users with Management access
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithManagementAccess($query)
    {
        return $query->where('is_allow_management_access', true);
    }

    /**
     * Toggle MIS access for a user
     *
     * @param int $userId
     * @return bool
     */
    public static function toggleMisAccess($userId)
    {
        $misUser = static::getForUser($userId);
        $misUser->is_allow_mis = !$misUser->is_allow_mis;
        return $misUser->save();
    }

    /**
     * Toggle Management access for a user
     *
     * @param int $userId
     * @return bool
     */
    public static function toggleManagementAccess($userId)
    {
        $misUser = static::getForUser($userId);
        $misUser->is_allow_management_access = !$misUser->is_allow_management_access;
        return $misUser->save();
    }
}
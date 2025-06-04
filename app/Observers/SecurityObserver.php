<?php

namespace App\Observers;

use App\Models\Submission\Security;
use Carbon\Carbon;

class SecurityObserver
{

    public function saving(Security $security)
    {
        // Check if expiry_date is in the past and status is active
        if ($security->expiry_date && $security->status === 'active' && Carbon::now()->gt($security->expiry_date)) {
            $security->status = 'expired';
        }
    }

    /**
     * Handle the Security "created" event.
     */
    public function created(Security $security): void
    {
        //
    }

    /**
     * Handle the Security "updated" event.
     */
    public function updated(Security $security): void
    {
        //
    }

    /**
     * Handle the Security "deleted" event.
     */
    public function deleted(Security $security): void
    {
        //
    }

    /**
     * Handle the Security "restored" event.
     */
    public function restored(Security $security): void
    {
        //
    }

    /**
     * Handle the Security "force deleted" event.
     */
    public function forceDeleted(Security $security): void
    {
        //
    }
}

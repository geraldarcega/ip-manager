<?php

namespace App\Observers;

use App\Models\IpAddress;
use Illuminate\Support\Facades\Auth;

class IpAddressObserver
{
    /**
     * Handle the IpAddress "creating" event.
     */
    public function creating(IpAddress $ipAddress): void
    {
        if ( !$ipAddress->isDirty('created_by')) {
            $ipAddress->created_by = Auth::user()?->id;
        }
    }

    /**
     * Handle the IpAddress "updating" event.
     */
    public function updating(IpAddress $ipAddress): void
    {
        if (!$ipAddress->isDirty('updated_by')) {
            $ipAddress->updated_by = Auth::user()?->id;
        }
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
    // Allow any authenticated user to view the listing; per-item policies restrict actions.
    return $user !== null;
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->user_id || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        // Allow any authenticated user to create a vehicle
        return $user !== null;
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->user_id || $user->hasRole('admin');
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->id === $vehicle->user_id || $user->hasRole('admin');
    }

    public function restore(User $user, Vehicle $vehicle): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Vehicle $vehicle): bool
    {
        return $user->hasRole('admin');
    }
}

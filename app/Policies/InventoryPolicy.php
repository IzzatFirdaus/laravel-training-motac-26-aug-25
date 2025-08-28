<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InventoryPolicy
{
    /**
     * Determine whether the user can view any inventories.
     *
     * Admins may browse all inventories; regular users may only browse their own via controller filters.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the specific inventory.
     */
    public function view(User $user, Inventory $inventory): bool
    {
        // Owner or admin may view
        return $user->id === $inventory->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create inventories.
     */
    public function create(User $user): bool
    {
        // Creating inventories is an admin-level action in this application.
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the inventory.
     */
    public function update(User $user, Inventory $inventory): bool
    {
        // Allow owner or admin to update
        return $user->id === $inventory->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the inventory (soft-delete).
     */
    public function delete(User $user, Inventory $inventory): bool
    {
        // Only admins may delete inventories
        return $user->id === $inventory->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the inventory.
     */
    public function restore(User $user, Inventory $inventory): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the inventory.
     */
    public function forceDelete(User $user, Inventory $inventory): bool
    {
        return $user->hasRole('admin');
    }
}

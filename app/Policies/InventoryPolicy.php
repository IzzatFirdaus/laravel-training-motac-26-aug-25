<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;

class InventoryPolicy
{
    /**
     * Determine whether the user can view any inventories.
     *
     * Admins and authenticated users may browse inventories; per-item policies still restrict view/update/delete.
     */
    public function viewAny(User $user): bool
    {
        // Allow any authenticated user to view the index/listing.
        return $user !== null;
    }

    /**
     * Determine whether the user can view the specific inventory.
     */
    public function view(User $user, Inventory $inventory): bool
    {
        // Owner or admin may view
        return $user->is($inventory->user) || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create inventories.
     */
    public function create(User $user): bool
    {
        // Allow any authenticated user to create their own inventory. Admins still allowed.
        return $user !== null;
    }

    /**
     * Determine whether the user can update the inventory.
     */
    public function update(User $user, Inventory $inventory): bool
    {
        // Allow owner or admin to update
        return $user->is($inventory->user) || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the inventory (soft-delete).
     */
    public function delete(User $user, Inventory $inventory): bool
    {
        // Owner or admin may delete
        return $user->is($inventory->user) || $user->hasRole('admin');
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

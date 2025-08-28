<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        // Only admins may create other users via the UI
        return $user->hasRole('admin');
    }

    public function update(User $user, User $model): bool
    {
        // Users may update their own profile; admins may update any.
        return $user->id === $model->id || $user->hasRole('admin');
    }

    public function delete(User $user, User $model): bool
    {
        // Admins may delete users; disallow self-delete through policy (controller blocks self-delete already)
        return $user->hasRole('admin');
    }
}

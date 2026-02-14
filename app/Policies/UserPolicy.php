<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageUsers();
    }

    public function view(User $user, User $model): bool
    {
        return $user->canManageUsers() || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->canManageUsers();
    }

    public function update(User $user, User $model): bool
    {
        // Admin kann immer alles, Manager kann alles außer Admins
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isManager() && !$model->isAdmin()) {
            return true;
        }
        // User kann sich selbst bearbeiten
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isManager() && !$model->isAdmin() && $user->id !== $model->id) {
            return true;
        }
        return false;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}

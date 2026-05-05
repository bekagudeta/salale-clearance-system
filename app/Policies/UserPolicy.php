<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasRole('super_admin');
    }

    public function view(User $user, User $model)
    {
        return $user->hasRole('super_admin') || $user->id === $model->id;
    }

    public function create(User $user)
    {
        return $user->hasRole('super_admin');
    }

    public function update(User $user, User $model)
    {
        return $user->hasRole('super_admin') || $user->id === $model->id;
    }

    public function delete(User $user, User $model)
    {
        return $user->hasRole('super_admin') && $user->id !== $model->id;
    }

    public function assignRole(User $user, User $model)
    {
        return $user->hasRole('super_admin');
    }
}
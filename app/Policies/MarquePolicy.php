<?php

namespace App\Policies;

use App\Models\Marque;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MarquePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Marque $marque): Response
    {
        return  $marque->user_id === $user->id ? Response::allow() : Response::deny("Vous n'êtes pas propiétaire de cette marque");;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Marque $marque): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Marque $marque): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Marque $marque): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Marque $marque): bool
    {
        return false;
    }
}

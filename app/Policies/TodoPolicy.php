<?php

namespace App\Policies;

use App\Models\Todo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User $user
     * @param  \App\Todo $todo
     * @return mixed
     */
    public function view(User $user, Todo $todo)
    {
        return $todo->isOwner($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User $user
     * @param  \App\Todo $todo
     * @return mixed
     */
    public function update(User $user, Todo $todo)
    {
        return $todo->isOwner($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User $user
     * @param  \App\Todo $todo
     * @return mixed
     */
    public function delete(User $user, Todo $todo)
    {
        return $todo->isOwner($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User $user
     * @param  \App\Todo $todo
     * @return mixed
     */
    public function restore(User $user, Todo $todo)
    {
        return $todo->isOwner($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User $user
     * @param  \App\Todo $todo
     * @return mixed
     */
    public function forceDelete(User $user, Todo $todo)
    {
        return $todo->isOwner($user);
    }

    public function complete(User $user, Todo $todo)
    {
        return $todo->isOwner($user);
    }


}

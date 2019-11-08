<?php

namespace App\Policies;

use App\Models\Control;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ControlPolicy
{
    use HandlesAuthorization;

    /**
     * filter function
     *
     * @param [type] $user
     * @param [type] $ability
     * @return void
     */
    public function before($user, $ability)
    {
        if ($user->isAdministrator()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the control.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Control  $control
     * @return mixed
     */
    public function view(User $user, Control $control)
    {
        //
    }

    /**
     * Determine whether the user can view the control.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Control  $control
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can create controls.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the control.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Control  $control
     * @return mixed
     */
    public function update(User $user, Control $control)
    {
        //
    }

    /**
     * Determine whether the user can delete the control.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Control  $control
     * @return mixed
     */
    public function delete(User $user, Control $control)
    {
        //
    }

    /**
     * Determine whether the user can restore the control.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Control  $control
     * @return mixed
     */
    public function restore(User $user, Control $control)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the control.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Control  $control
     * @return mixed
     */
    public function forceDelete(User $user, Control $control)
    {
        //
    }

    /**
     * Determine whether the user can bulk delete the control.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Control  $control
     * @return mixed
     */
    public function bulkDelete(User $user)
    {
        //
    }
}

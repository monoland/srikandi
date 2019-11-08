<?php

namespace App\Policies;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BalancePolicy
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
     * Determine whether the user can view the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function view(User $user, Balance $balance)
    {
        //
    }

    /**
     * Determine whether the user can view the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can create balances.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function update(User $user, Balance $balance)
    {
        //
    }

    /**
     * Determine whether the user can delete the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function delete(User $user, Balance $balance)
    {
        //
    }

    /**
     * Determine whether the user can restore the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function restore(User $user, Balance $balance)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function forceDelete(User $user, Balance $balance)
    {
        //
    }

    /**
     * Determine whether the user can bulk delete the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function bulkDelete(User $user)
    {
        //
    }
}

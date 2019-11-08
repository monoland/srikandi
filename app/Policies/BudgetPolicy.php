<?php

namespace App\Policies;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BudgetPolicy
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
     * Determine whether the user can view the budget.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return mixed
     */
    public function view(User $user, Budget $budget)
    {
        //
    }

    /**
     * Determine whether the user can view the budget.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can create budgets.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the budget.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return mixed
     */
    public function update(User $user, Budget $budget)
    {
        //
    }

    /**
     * Determine whether the user can delete the budget.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return mixed
     */
    public function delete(User $user, Budget $budget)
    {
        //
    }

    /**
     * Determine whether the user can restore the budget.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return mixed
     */
    public function restore(User $user, Budget $budget)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the budget.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return mixed
     */
    public function forceDelete(User $user, Budget $budget)
    {
        //
    }

    /**
     * Determine whether the user can bulk delete the budget.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return mixed
     */
    public function bulkDelete(User $user)
    {
        //
    }
}

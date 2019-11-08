<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
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
     * Determine whether the user can view the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function view(User $user, Service $service)
    {
        //
    }

    /**
     * Determine whether the user can view the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create services.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isOperator();
    }

    /**
     * Determine whether the user can update the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function update(User $user, Service $service)
    {
        if ($user->isOperator()) {
            return $service->status === 'drafted';
        }
    }

    /**
     * Determine whether the user can delete the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function delete(User $user, Service $service)
    {
        if ($user->isOperator()) {
            return $service->status === 'drafted';
        }
    }

    /**
     * Determine whether the user can restore the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function restore(User $user, Service $service)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function forceDelete(User $user, Service $service)
    {
        //
    }

    /**
     * Determine whether the user can bulk delete the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function bulkDelete(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function drafted(User $user, Service $service)
    {
        return $user->isOperator() && $service->status === 'drafted';
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @param Service $service
     * @return void
     */
    public function dispositioned(User $user, Service $service)
    {
        return $user->isKabiro() && $service->status === 'dispositioned';
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @param Service $service
     * @return void
     */
    public function submissioned(User $user, Service $service)
    {
        return $user->isPPTK() && $service->status === 'submissioned';
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @param Service $service
     * @return void
     */
    public function examined(User $user, Service $service)
    {
        return $user->isKPA() && $service->status === 'examined';
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @param Service $service
     * @return void
     */
    public function approved(User $user, Service $service)
    {
        return $user->isOperator() && $service->status === 'approved';
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @param Service $service
     * @return void
     */
    public function printed(User $user, Service $service)
    {
        return $user->isTataUsaha() && $service->status === 'printed';
    }
}

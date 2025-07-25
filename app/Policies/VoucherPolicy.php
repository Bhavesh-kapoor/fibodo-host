<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Auth\Access\Response;

class VoucherPolicy
{

    /**
     * owner
     *
     * @param  mixed $user
     * @param  mixed $voucher
     * @return bool
     */
    public function owner(User $user, Voucher $voucher): bool
    {
        return $user->canAccess($voucher->host_id) && $user->isHost();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isHost();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Voucher $voucher): bool
    {
        return $this->owner($user, $voucher);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isHost();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Voucher $voucher): bool
    {
        return $this->owner($user, $voucher);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Voucher $voucher): bool
    {
        return $this->owner($user, $voucher);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Voucher $voucher): bool
    {
        return $this->owner($user, $voucher);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Voucher $voucher): bool
    {
        return $this->owner($user, $voucher);
    }
}

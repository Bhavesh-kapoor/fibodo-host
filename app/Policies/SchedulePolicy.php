<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Schedules\Schedule;
use App\Models\User;

class SchedulePolicy
{

    /**
     * owner
     *
     * @param  mixed $user
     * @param  mixed $product
     * @return bool
     */
    public function owner(User $user, Product $product): bool
    {
        return $user->isProductOwner($product->user_id) && $user->isHost();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Product $product = null): bool
    {
        return $product ? $this->owner($user, $product) : $user->isHost();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        return $this->owner($user, $schedule->product);
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        return $this->owner($user, $schedule->product);
    }

    /**
     * Determine whether the user can rename the model.
     */
    public function rename(User $user, Schedule $schedule): bool
    {
        dd('hi');
        return $this->owner($user, $schedule->product);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schedule $schedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schedule $schedule): bool
    {
        return false;
    }
}

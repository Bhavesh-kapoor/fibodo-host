<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
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
    public function viewAny(User $user): bool
    {
        return $user->isHost();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        return $this->owner($user, $product);
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
    public function update(User $user, Product $product): bool
    {
        return $this->owner($user, $product);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return $this->owner($user, $product);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return $this->owner($user, $product);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $this->owner($user, $product);
    }

    /**
     * Determine whether the user can access location.
     */
    public function LocationAccess(User $user, Product $product): bool
    {
        return $this->owner($user, $product);
    }

    /**
     * Determine whether the user can access scheudles.
     */
    public function ScheduleAccess(User $user, Product $product): bool
    {
        return $this->owner($user, $product);
    }

    /**
     * Determine whether the user can access settings.
     */
    public function AttendeeSettingAccess(User $user, Product $product): bool

    {
        return $user->isProductOwner($product->user_id) && $user->isHost();
    }
    public function PriceAccess(User $user, Product $product): bool
    {
        return $user->isProductOwner($product->user_id) && $user->isHost();
    }
}

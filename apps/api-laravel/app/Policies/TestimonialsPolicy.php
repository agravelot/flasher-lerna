<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Testimonial;
use App\Models\User;

class TestimonialsPolicy extends Policy
{
    /**
     * Determine whether the user can view the categorys.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the category.
     */
    public function view(User $user, ?Testimonial $testimonialPost = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create testimonial post.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the category.
     */
    public function update(User $user, Testimonial $testimonialPost): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the category.
     */
    public function delete(User $user, ?Testimonial $testimonialPost = null): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the category.
     */
    public function restore(User $user, Testimonial $testimonialPost): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the category.
     */
    public function forceDelete(User $user, Testimonial $testimonialPost): bool
    {
        return false;
    }
}

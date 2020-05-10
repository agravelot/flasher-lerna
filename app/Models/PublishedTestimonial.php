<?php

declare(strict_types=1);

namespace App\Models;

use App\Abilities\HasParentModel;

class PublishedTestimonial extends Testimonial
{
    use HasParentModel;

    public static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(static function ($query) {
            $query->published();
        });
    }
}

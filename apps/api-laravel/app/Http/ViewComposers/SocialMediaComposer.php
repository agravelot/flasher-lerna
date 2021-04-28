<?php

declare(strict_types=1);

namespace App\Http\ViewComposers;

use App\Models\SocialMedia;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class SocialMediaComposer
{
    private Collection $socialMedias;

    /**
     * Bind data to the view.
     */
    public function create(View $view): void
    {
        $view->with('socialMedias', $this->socialMedias ??= SocialMedia::active()->get());
    }
}

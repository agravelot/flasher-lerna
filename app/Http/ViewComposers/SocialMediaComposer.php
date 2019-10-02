<?php

namespace App\Http\ViewComposers;

use App\Models\SocialMedia;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class SocialMediaComposer
{
    /**
     * @var Collection
     */
    private $socialMedias;

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (! $this->socialMedias) {
            $this->socialMedias = SocialMedia::active()->get();
        }

        $view->with('socialMedias', $this->socialMedias);
    }
}

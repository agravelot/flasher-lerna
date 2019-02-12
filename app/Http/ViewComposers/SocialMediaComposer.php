<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

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
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        if (! $this->socialMedias) {
            $this->socialMedias = SocialMedia::active()->get();
        }

        $view->with('socialMedias', $this->socialMedias);
    }
}

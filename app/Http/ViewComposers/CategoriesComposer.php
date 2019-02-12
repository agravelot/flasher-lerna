<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Http\ViewComposers;

use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoriesComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $view->with('categories', Category::all());
    }
}

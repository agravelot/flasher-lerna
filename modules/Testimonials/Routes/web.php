<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */


Route::namespace('Front')->group(function () {
    Route::resource('testimonials', 'GoldenBookController')->only(['index', 'create', 'store']);
});

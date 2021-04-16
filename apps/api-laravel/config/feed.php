<?php

declare(strict_types=1);

return [
    'feeds' => [
        'main' => [
            /*
             * Here you can specify which class and method will return
             * the items that should appear in the feed. For example:
             * 'App\Model@getAllFeedItems'
             *
             * You can also pass an argument to that method:
             * ['App\Model@getAllFeedItems', 'argument']
             */
            'items' => 'App\Models\PublicAlbum@all',

            /*
             * The feed will be available on this url.
             */
            'url' => '/feed',

            'title' => 'Jkanda',
            'description' => '',
            'language' => 'fr-FR',

            /*
             * The view that will render the feed.
             */
            'view' => 'feed::feed',
        ],
    ],
];
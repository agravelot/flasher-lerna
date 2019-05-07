<?php

/*
 * (c) Antoine GRAVELOT <antoine.gravelot@hotmail.fr> - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Antoine Gravelot <agravelot@hotmail.fr>
 */

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Cosplayer;
use App\Models\PublicAlbum;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Console\Command;
use Psr\Http\Message\UriInterface;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = SitemapGenerator::create(config('app.url'))
            ->shouldCrawl(function (UriInterface $url) {
                return mb_strpos($url->getPath(), '/admin') === false;
            })->getSitemap();

        PublicAlbum::all()->each(function (Album $album) use ($sitemap) {
            $sitemap->add(Url::create(route('albums.show', compact('album')))
                ->setPriority(0.9)->setLastModificationDate($album->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        });

        Cosplayer::all()->each(function (Cosplayer $cosplayer) use ($sitemap) {
            $sitemap->add(Url::create(route('cosplayers.show', compact('cosplayer')))
                ->setPriority(0.6)->setLastModificationDate($cosplayer->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        });

        $sitemap->writeToFile(storage_path('sitemap.xml'));
    }
}

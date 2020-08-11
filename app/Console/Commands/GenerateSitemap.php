<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Category;
use App\Models\Cosplayer;
use App\Models\PublicAlbum;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Psr\Http\Message\UriInterface;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

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
    public function handle(): void
    {
        $sitemap = SitemapGenerator::create(config('app.url'))
            ->shouldCrawl(static function (UriInterface $uri) {
                return ! Str::startsWith($uri->getPath(), ['/albums/', '/cosplayers/', '/categories/', '/admin']);
            })->getSitemap();

        PublicAlbum::chunk(10, static function (Collection $albums) use ($sitemap): void {
            $albums->each(static function (Album $album) use ($sitemap): void {
                $sitemap->add(Url::create(route('albums.show', compact('album')))
                    ->setPriority(1.0)
                    ->setLastModificationDate($album->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            });
        });

        Cosplayer::chunk(10, static function (Collection $cosplayers) use ($sitemap): void {
            $cosplayers->each(static function (Cosplayer $cosplayer) use ($sitemap): void {
                $sitemap->add(Url::create(route('cosplayers.show', compact('cosplayer')))
                    ->setPriority(0.6)
                    ->setLastModificationDate($cosplayer->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
            });
        });

        Category::chunk(10, static function (Collection $categories) use ($sitemap): void {
            $categories->each(static function (Category $category) use ($sitemap): void {
                $sitemap->add(Url::create(route('categories.show', compact('category')))
                    ->setPriority(0.6)
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
            });
        });

        $sitemap->writeToFile(storage_path('app/public/sitemap.xml'));
    }
}

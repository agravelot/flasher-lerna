<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifySitemapUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->guardAgainstNotRunningInProduction();

        $siteMapUrl = url('/sitemap.xml');
        $url = 'https://www.google.com/webmasters/tools/ping?sitemap='.$siteMapUrl;
        $data = file_get_contents($url);
        $success = strpos($data, 'Sitemap Notification Received') !== false;
        if (! $success) {
            $this->fail(new \Exception(
                'Unable to send google sitemap update notification'
            ));
        }
    }

    public function guardAgainstNotRunningInProduction(): void
    {
        if (! App::environment('production')) {
            $this->delete();
        }
    }
}

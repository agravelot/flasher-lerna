<?php

namespace App\Http\Schemas;

use App\Models\SocialMedia;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\Thing;

class HomepageSchema implements SchemaInterface
{
    public function __toString(): string
    {
        $schema = Schema::photograph()
            ->name(settings()->get('app_name'))
            ->alternateName('Jujune kanda')
            ->sameAs(SocialMedia::active()->get()->pluck('url')->toArray())
            ->audience([
                'CreativeWork',
                'Event',
                'Service',
            ])
            ->about((new Thing())
                ->name(settings()->get('seo_description'))
            )
            ->isAccessibleForFree(true)
            ->keywords([
                'photographe',
                'cosplay',
            ]);

        return $schema->toScript();
    }
}

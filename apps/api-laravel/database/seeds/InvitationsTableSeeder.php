<?php

declare(strict_types=1);

use App\Models\Cosplayer;
use App\Models\Invitation;
use Illuminate\Database\Seeder;

class InvitationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cosplayer::take(10)->get()->each(static function (Cosplayer $cosplayer): void {
            factory(Invitation::class)->create([
                'cosplayer_id' => $cosplayer->id,
            ]);
        });
    }
}

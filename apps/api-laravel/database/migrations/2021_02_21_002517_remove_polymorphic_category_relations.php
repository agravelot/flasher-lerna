<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePolymorphicCategoryRelations extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('album_category');

        Schema::create('album_category', static function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('album_id');
            $table->unsignedInteger('category_id');
            $table->timestamps();
        });

        Schema::table('album_category', static function (Blueprint $table): void {
            $table->foreign('album_id')->references('id')->on('albums')
                ->onDelete('set null')
                ->onUpdate('set null');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('set null')
                ->onUpdate('set null');
        });

        DB::beginTransaction();
        $categorizables = DB::table('categorizables')->where('categorizable_type', 'App\Models\Album')->get();
        foreach ($categorizables as $categorizable) {
            $album = DB::table('albums')->find($categorizable->categorizable_id);
            $category = DB::table('categories')->find($categorizable->category_id);
            if ($album === null || $category === null) {
                continue;
            }
            DB::table('album_category')->insert([
                'album_id' => $album->id,
                'category_id' => $category->id,
            ]);
        }
        DB::commit();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_category');
    }
}

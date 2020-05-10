<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddCategoryMetaDescription extends Migration
{
    public function up(): void
    {
        Schema::table('categories', static function (Blueprint $table) {
            $table->string('meta_description', 155)->nullable();
        });

        DB::table('categories')->orderBy('id')->chunk(10, static function (Collection $categories) {
            $categories->each(static function ($category) {
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update([
                        'meta_description' => Str::limit(strip_tags($category->description), 150) ?? $category->name,
                    ]);
            });
        });

        Schema::table('categories', static function (Blueprint $table) {
            $table->string('meta_description', 155)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('categories', static function (Blueprint $table) {
            $table->dropColumn('meta_description');
        });
    }
}

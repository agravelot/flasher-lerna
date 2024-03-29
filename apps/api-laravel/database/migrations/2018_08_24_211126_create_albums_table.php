<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('albums', static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title')->unique();
            $table->text('body')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->boolean('private');
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
}

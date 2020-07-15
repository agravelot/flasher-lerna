<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategorizableMustNotHaveDuplicate extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('categorizables')->get()->each(static function($categorizable) {
            DB::table('categorizables')->where((array) $categorizable)->delete();
            DB::table('categorizables')->insert((array) $categorizable);
        });

        Schema::table('categorizables', static function (Blueprint $table): void {
            $table->unique(['category_id', 'categorizable_type', 'categorizable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categorizables', static function (Blueprint $table): void {
            $table->dropIndex(['category_id', 'categorizable_type', 'categorizable_id']);
        });
    }
}

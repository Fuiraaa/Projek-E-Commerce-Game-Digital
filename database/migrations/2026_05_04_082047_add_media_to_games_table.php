<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            if (!Schema::hasColumn('games', 'gallery_photos')) {
                $table->json('gallery_photos')->nullable();
            }
            if (!Schema::hasColumn('games', 'trailer_video')) {
                $table->string('trailer_video')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['gallery_photos', 'trailer_video']);
        });
    }
};

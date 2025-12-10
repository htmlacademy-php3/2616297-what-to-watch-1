<?php

declare(strict_types=1);

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
        Schema::table('films', function (Blueprint $table) {
            $table->dropForeign(['genre_id']);
            $table->dropColumn('genre_id');
        });

        Schema::create('film_genre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('film_id')->constrained();
            $table->foreignId('genre_id')->constrained();
            $table->unique(['film_id', 'genre_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film_genre');

        Schema::table('films', function (Blueprint $table) {
            $table->foreignId('genre_id')->nullable()->constrained();
        });
    }
};

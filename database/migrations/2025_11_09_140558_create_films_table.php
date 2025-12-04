<?php

use App\Enums\FilmStatus;
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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('poster_image', 255)->nullable();
            $table->string('preview_image', 255)->nullable();
            $table->string('background_image', 255)->nullable();
            $table->string('background_color', 9)->nullable();
            $table->string('video_link', 255)->nullable();
            $table->string('preview_video_link', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('director', 255)->nullable();
            $table->string('starring', 255)->nullable();
            $table->foreignId('genre_id')->nullable()->constrained();
            $table->integer('run_time')->nullable();
            $table->integer('released')->nullable();
            $table->string('imdb_id', 255);
            $table->enum('status', FilmStatus::values())->default(FilmStatus::READY->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};

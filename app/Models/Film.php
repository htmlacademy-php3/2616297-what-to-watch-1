<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property null|string $poster_image
 * @property null|string $preview_image
 * @property null|string $background_image
 * @property null|string $background_color
 * @property null|string $video_link
 * @property null|string $preview_video_link
 * @property null|string $description
 * @property null|string $director
 * @property null|string $starring
 * @property int $genre_id
 * @property null|int $run_time
 * @property null|int $released
 * @property string $imdb_id
 * @property string $status
 * @property-read float $rating
 */
class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'poster_image',
        'preview_image',
        'background_image',
        'background_color',
        'video_link',
        'preview_video_link',
        'description',
        'director',
        'starring',
        'genre_id',
        'run_time',
        'released',
        'imdb_id',
        'status',
    ];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_films');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getRatingAttribute(): float
    {
        return round(
            $this->comments->avg('rating'),
            1
        );
    }
}
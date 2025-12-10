<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FilmFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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
 * @property null|int $run_time
 * @property null|int $released
 * @property string $imdb_id
 * @property string $status
 * @property-read float $rating
 * @property int $id
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Comment> $comments
 * @property-read int|null $comments_count
 * @property-read Genre $genre
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static FilmFactory factory($count = null, $state = [])
 * @method static Builder<static>|Film newModelQuery()
 * @method static Builder<static>|Film newQuery()
 * @method static Builder<static>|Film query()
 * @method static Builder<static>|Film whereBackgroundColor($value)
 * @method static Builder<static>|Film whereBackgroundImage($value)
 * @method static Builder<static>|Film whereCreatedAt($value)
 * @method static Builder<static>|Film whereDescription($value)
 * @method static Builder<static>|Film whereDirector($value)
 * @method static Builder<static>|Film whereGenreId($value)
 * @method static Builder<static>|Film whereId($value)
 * @method static Builder<static>|Film whereImdbId($value)
 * @method static Builder<static>|Film wherePosterImage($value)
 * @method static Builder<static>|Film wherePreviewImage($value)
 * @method static Builder<static>|Film wherePreviewVideoLink($value)
 * @method static Builder<static>|Film whereReleased($value)
 * @method static Builder<static>|Film whereRunTime($value)
 * @method static Builder<static>|Film whereStarring($value)
 * @method static Builder<static>|Film whereStatus($value)
 * @method static Builder<static>|Film whereTitle($value)
 * @method static Builder<static>|Film whereUpdatedAt($value)
 * @method static Builder<static>|Film whereVideoLink($value)
 * @property int|null $is_promo
 * @property-read Collection<int, Genre> $genres
 * @property-read int|null $genres_count
 * @method static Builder<static>|Film whereIsPromo($value)
 * @mixin Eloquent
 * @mixin Builder
 */
final class Film extends Model
{
    /** @use HasFactory<FilmFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'poster_image',
        'preview_image',
        'background_image',
        'background_color',
        'video_link',
        'preview_video_link',
        'description',
        'director',
        'starring',
        'run_time',
        'released',
        'imdb_id',
        'status',
    ];

    /**
     * @return BelongsToMany
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'film_genre');
    }

    /**
     * @return BelongsToMany
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_films');
    }

    /**
     * @return HasMany
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Возвращает рейтинг исходя из отзывов о фильме
     *
     * @return float
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getRatingAttribute(): float
    {
        /** @var float|null $avg */
        $avg = $this->comments->avg('rating');

        return round(
            $avg ?? 0.0,
            1
        );
    }
}
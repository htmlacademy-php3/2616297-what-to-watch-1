<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CommentFactory;
use Database\Factories\FilmFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $film_id
 * @property string $text
 * @property string $author_name
 * @property int $rating
 * @property null|int $comment_id
 * @property int $id
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Film $film
 * @property-read User|null $user
 * @method static CommentFactory factory($count = null, $state = [])
 * @method static Builder<Comment> newModelQuery()
 * @method static Builder<Comment> newQuery()
 * @method static Builder<Comment> query()
 * @method static Builder<Comment> whereCommentId($value)
 * @method static Builder<Comment> whereCreatedAt($value)
 * @method static Builder<Comment> whereFilmId($value)
 * @method static Builder<Comment> whereId($value)
 * @method static Builder<Comment> whereRating($value)
 * @method static Builder<Comment> whereText($value)
 * final
 * @method static Builder<Comment> whereUpdatedAt($value)
 * @method static Builder<Comment> whereUserId($value)
 */
final class Comment extends Model
{
    /** @use HasFactory<FilmFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'film_id',
        'text',
        'rating',
        'comment_id',
        'user_id'
    ];

    /**
     * @return BelongsTo
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    /**
     * @return BelongsTo
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getAuthorNameAttribute(): string
    {
        return null !== $this->user ? $this->user->name : 'Аноним';
    }
}
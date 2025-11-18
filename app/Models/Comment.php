<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $film_id
 * @property string $text
 * @property int $rating
 * @property null|int $comment_id
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'film_id',
        'text',
        'rating',
        'comment_id'
    ];

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
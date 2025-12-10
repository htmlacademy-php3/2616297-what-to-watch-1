<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\GenreFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property string $name
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Film> $films
 * @property-read int|null $films_count
 * @method static GenreFactory factory($count = null, $state = [])
 * @method static Builder<static>|Genre newModelQuery()
 * @method static Builder<static>|Genre newQuery()
 * @method static Builder<static>|Genre query()
 * @method static Builder<static>|Genre whereCreatedAt($value)
 * @method static Builder<static>|Genre whereId($value)
 * @method static Builder<static>|Genre whereName($value)
 * @method static Builder<static>|Genre whereUpdatedAt($value)
 * @mixin Eloquent
 * @mixin Builder
 */
final class Genre extends Model
{
    /** @use HasFactory<GenreFactory> */
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Связь с фильмами
     *
     * @return BelongsToMany
     */
    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'film_genre');
    }
}
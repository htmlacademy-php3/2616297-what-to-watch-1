<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\CommentData;
use App\Data\UpdateCommentData;
use App\Models\Comment;
use Override;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Реализация репозитория комментариев
 */
final class CommentRepository implements CommentRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getAllByFilm(int $filmId): array
    {
        /**
         * @var LengthAwarePaginator $paginator
         * @psalm-suppress UnnecessaryVarAnnotation
         */
        $paginator = Comment::where('film_id', $filmId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return $paginator->toArray();
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function create(CommentData $data): void
    {
        Comment::create($data->toArray());
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function update(UpdateCommentData $data): void
    {
        Comment::findOrFail($data->id)
            ->update(
                $data->toArray()
            );
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
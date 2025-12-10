<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\CommentData;
use App\Data\UpdateCommentData;
use App\Models\Comment;
use App\Repositories\CommentRepositoryInterface;

/**
 * Сервис для работы с комментариями
 */
readonly final class CommentService
{

    /**
     * Внедряет зависимости
     *
     * @param CommentRepositoryInterface $commentRepository
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private CommentRepositoryInterface $commentRepository,
    ) {
    }

    /**
     * Получает комментарии по идентификатору фильма
     *
     * @param int $filmId
     * @return array
     */
    public function getForFilm(int $filmId): array
    {
        return $this->commentRepository->getAllByFilm($filmId);
    }

    /**
     * Создаёт новый комментарий
     *
     * @param CommentData $data
     * @return void
     */
    public function create(CommentData $data): void
    {
        $this->commentRepository->create($data);
    }

    /**
     * Обновляет комментарий
     *
     * @param UpdateCommentData $data
     * @return void
     */
    public function update(UpdateCommentData $data): void
    {
        $this->commentRepository->update($data);
    }

    /**
     * Удаляет комментарий
     *
     * @param Comment $comment
     * @return void
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }
}
<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\CommentData;
use App\Data\UpdateCommentData;
use App\Models\Comment;

/**
 * Интерфейс репозитория для работы с комментариями к фильму
 */
interface CommentRepositoryInterface
{
    /**
     * Получает все комментарии по фильму с пагинацией
     *
     * @param int $filmId
     * @return array
     */
    public function getAllByFilm(int $filmId): array;

    /**
     * Создаёт новый комментарий из переданных данных
     *
     * @param CommentData $data
     * @return void
     */
    public function create(CommentData $data): void;

    /**
     * Обновляет комментарий новыми данными
     *
     * @param UpdateCommentData $data
     * @return void
     */
    public function update(UpdateCommentData $data): void;

    /**
     * Удаляет комментарий
     *
     * @param Comment $comment
     * @return void
     */
    public function delete(Comment $comment): void;
}
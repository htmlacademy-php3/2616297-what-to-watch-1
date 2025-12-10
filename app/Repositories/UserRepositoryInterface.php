<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\RegisterUserData;
use App\Data\UpdateUserData;

/**
 *  Интерфейс репозитория для работы с пользователями
 */
interface UserRepositoryInterface
{
    /**
     * Создаёт нового пользователя
     *
     * @param RegisterUserData $DTO
     * @return int
     */
    public function create(RegisterUserData $DTO): int;

    /**
     * Получает пользователя по его идентификатору
     *
     * @param int|null $id
     * @return array
     */
    public function getById(?int $id): array;

    /**
     * Обновляет данные пользователя
     *
     * @param UpdateUserData $data
     * @param int|null $userId
     * @return void
     */
    public function update(UpdateUserData $data, ?int $userId): void;
}
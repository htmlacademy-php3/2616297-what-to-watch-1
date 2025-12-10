<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\UpdateUserData;
use App\Repositories\UserRepositoryInterface;

/**
 * Сервис для работы с пользователями
 */
readonly final class UserService
{

    /**
     * Внедряет зависимости
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    /**
     * Получает данные о текущем пользователе
     *
     * @param int|null $id
     * @return array
     * @throws \Exception
     */
    public function getUserData(?int $id): array
    {
        return $this->userRepository->getById($id);
    }

    /**
     * Обновляет данные текущего пользователя
     *
     * @param UpdateUserData $data
     * @param int|null $userId
     * @return void
     */
    public function updateUserData(UpdateUserData $data, ?int $userId): void
    {
        $this->userRepository->update($data, $userId);
    }
}
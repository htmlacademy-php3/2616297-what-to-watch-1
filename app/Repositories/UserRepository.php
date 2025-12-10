<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\RegisterUserData;
use App\Data\UpdateUserData;
use App\Http\Resources\UserResource;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;
use Override;

/**
 * Реализация репозитория для работы с пользователями
 */
final class UserRepository implements UserRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    #[Override]
    public function create(RegisterUserData $DTO): int
    {
        $userData = [
            'email' => $DTO->email,
            'password' => Hash::make($DTO->password),
            'name' => $DTO->name,
            'profile_picture' => $DTO->file
        ];

        $user = UserModel::create($userData);

        return $user->id;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getById(?int $id): array
    {
        if (null === $id) {
            throw new \Exception('User is not authorized');
        }

        /** @psalm-suppress UndefinedMagicMethod */
        $user = UserModel::select(
            [
                'name',
                'email',
                'profile_picture',
            ]
        )->findOrFail($id);

        return (new UserResource($user))->resolve();
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function update(UpdateUserData $data, ?int $userId): void
    {
        if (null === $userId) {
            throw new \Exception('User is not authorized');
        }

        UserModel::findOrFail($userId)
            ->update(
                array_filter(
                    $data->toArray()
                )
            );
    }
}
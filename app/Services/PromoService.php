<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PromoRepositoryInterface;

/**
 * Сервис для взаимодействия с промо фильмом
 */
readonly final class PromoService
{

    /**
     * Внедряет зависимости
     *
     * @param PromoRepositoryInterface $promoRepository
     */
    public function __construct(
        private PromoRepositoryInterface $promoRepository,
    ) {
    }

    /**
     * Возвращает текущий промо фильм
     *
     * @return array
     */
    public function getPromo(): array
    {
        return $this->promoRepository->getCurrentPromo();
    }

    /**
     * Устанавливает промо фильм
     *
     * @param int $filmId
     * @return void
     */
    public function setPromo(int $filmId): void
    {
        $this->promoRepository->setPromo($filmId);
    }
}
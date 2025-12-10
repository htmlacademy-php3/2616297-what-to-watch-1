<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\FilmService;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обрабатывает задачу по запросу и обновлению данных о фильме из IMDB API
 */
final class ProcessPendingFilm implements ShouldQueue
{
    use Queueable;

    /**
     * Создаёт новый экземпляр задачи
     */
    public function __construct(
        private int $filmId
    ) {

    }

    /**
     * Выполняет задачу
     */
    public function handle(FilmService $service): void
    {
        $service->updateWithIMDB($this->filmId);
    }
}

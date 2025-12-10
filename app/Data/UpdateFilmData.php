<?php

declare(strict_types=1);

namespace App\Data;

use App\Enums\FilmStatus;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * Data-объект с данными из запроса на обновление информации о фильме
 *
 */
final class UpdateFilmData extends Data
{
    /**
     * Список параметров запроса
     *
     * @param string $title
     * @param string|null $poster_image
     * @param string|null $preview_image
     * @param string|null $background_image
     * @param string|null $background_color
     * @param string|null $video_link
     * @param string|null $preview_video_link
     * @param string|null $description
     * @param string|null $director
     * @param array|null $starring
     * @param array|null $genre
     * @param int|null $run_time
     * @param int|null $released
     * @param FilmStatus $status
     */
    public function __construct(
        #[MapName('name', 'title')]
        public string $title,
        public FilmStatus $status,
        public ?string $poster_image = null,
        public ?string $preview_image = null,
        public ?string $background_image = null,
        public ?string $background_color = null,
        public ?string $video_link = null,
        public ?string $preview_video_link = null,
        public ?string $description = null,
        public ?string $director = null,
        public ?array $starring = null,
        public ?array $genre = null,
        public ?int $run_time = null,
        public ?int $released = null,
    ) {}

    /**
     * Список правил валидации параметров запроса
     *
     * @method static array rules(?ValidationContext $context = null)
     * @method static array messages(...$args)
     * @method static array attributes(...$args)
     * @method static bool stopOnFirstFailure()
     * @method static string redirect()
     * @method static string redirectRoute()
     * @method static string errorBag()
     */
    public static function rules(): array
    {
        return [
            'title' => ['required', 'max:255'],
            'poster_image' => ['max:255'],
            'preview_image' => ['max:255'],
            'background_image' => ['max:255'],
            'background_color' => ['max:9'],
            'video_link' => ['max:255'],
            'preview_video_link' => ['max:255'],
            'description' => ['max:1000'],
            'director' => ['max:255'],
            'starring' => ['array'],
            'starring.*' => ['string'],
            'genre' => ['array'],
            'genre.*' => ['string'],
            'run_time' => ['integer', 'min:1'],
            'released' => ['integer', 'min:1888', 'max:' . (date('Y'))],
            'status' => ['required', Rule::in(FilmStatus::values())],
        ];
    }
}
<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = parent::toArray($request);

        if (!$request->user('sanctum')) {
            return $result;
        }

        return array_merge($result, [
            'in_favorite' => User::with('films')
                ->find(
                    $request
                        ->user('sanctum')
                        ->id
                )
                ->films
                ->contains('id', $this->id),
        ]);
    }
}

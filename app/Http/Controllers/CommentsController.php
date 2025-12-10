<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\CommentData;
use App\Data\UpdateCommentData;
use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\Comment;
use App\Services\CommentService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер ресурса комментариев
 *
 * @psalm-suppress UnusedClass
 */
final class CommentsController extends Controller
{
    /**
     * Обрабатывает запрос получения комментария по идентификатору фильма
     *
     * @param int $id Идентификатор фильма
     * @param CommentService $commentService
     * @return BaseResponse
     */
    public function index(int $id, CommentService $commentService): BaseResponse
    {
        return new SuccessResponse(
            $commentService->getForFilm($id)
        );
    }

    /**
     * Обрабатывает запрос добавления комментария по идентификатору фильма
     *
     * @param CommentService $service
     * @param CommentData $data
     * @return BaseResponse
     */
    public function create(CommentService $service, CommentData $data): BaseResponse
    {
        $service->create($data);

        return new SuccessResponse(
            [],
            Response::HTTP_CREATED
        );
    }

    /**
     * Обрабатывает запрос изменения комментария по идентификатору
     *
     * @param UpdateCommentData $data
     * @param CommentService $service
     * @return BaseResponse
     */
    public function update(UpdateCommentData $data, CommentService $service): BaseResponse
    {
        $service->update($data);

        return new SuccessResponse(
            [], Response::HTTP_OK
        );
    }


    /**
     * Обрабатывает запрос удаления комментария по идентификатору
     *
     * @param Comment $comment Идентификатор комментария
     * @param CommentService $commentService
     * @return BaseResponse
     */
    public function destroy(Comment $comment, CommentService $commentService): BaseResponse
    {
        $commentService->delete($comment);

        return new SuccessResponse(
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}
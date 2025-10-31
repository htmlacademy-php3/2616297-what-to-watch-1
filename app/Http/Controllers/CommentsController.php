<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\SuccessResponse;
use Symfony\Component\HttpFoundation\Response;

class CommentsController extends Controller
{
    public function index(): BaseResponse
    {
        return new SuccessResponse(['test'], Response::HTTP_CREATED);
    }

    public function create(): BaseResponse
    {
    }

    public function update(): BaseResponse
    {
    }


    public function destroy(): BaseResponse
    {
    }
}
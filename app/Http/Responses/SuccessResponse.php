<?php

namespace App\Http\Responses;

class SuccessResponse extends BaseResponse
{

    protected function prepareData(): mixed
    {
        return $this->data;
    }
}
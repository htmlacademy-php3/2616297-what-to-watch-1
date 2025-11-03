<?php

namespace App\Http\Responses;

class SuccessResponse extends BaseResponse
{
    protected function prepareData(): array
    {
        return [
            'data' => $this->data,
        ];
    }

    public function withPagination(array $paginationData): BaseResponse
    {
        $this->data = array_merge($this->data, $paginationData);
        return $this;
    }
}
<?php

namespace app\Movie;

interface MovieRepositoryInterface
{
    public function findById(int $id): Movie;
}
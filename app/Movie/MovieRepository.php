<?php

use Psr\Http\Client\ClientInterface;

class MovieRepository implements MovieRepositoryInterface
{
    public function __construct(
        private ClientInterface $client,
    ) {
    }

    public function findById(int $id): Movie
    {
        return new Movie();
    }
}
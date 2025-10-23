<?php

interface MovieRepositoryInterface
{
    public function findById(int $id): Movie;
}
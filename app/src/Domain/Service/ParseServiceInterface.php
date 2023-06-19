<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Post;

interface ParseServiceInterface
{
    public function post(string $rawData): string|null;

    public function clear(string $rawData): string;

    public function getPostFromString(string $data): Post;

    public function postToArray(Post $post): array;
}

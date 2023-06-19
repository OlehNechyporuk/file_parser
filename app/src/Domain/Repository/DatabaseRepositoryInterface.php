<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Database;

interface DatabaseRepositoryInterface
{
    /**
     * @return Database[]
     */
    public function list(): array;

    public function read(string $path): mixed;

    public function getPathByName(string $name): string;
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Database;
use App\Domain\Repository\DatabaseRepositoryInterface;

class OtherFileDatabaseRepository implements DatabaseRepositoryInterface
{
    /**
     * @return Database[]
     */
    public function list(): array
    {
        echo 'other';

        return [];
    }

    public function read(string $name): string
    {
        return '';
    }

    public function getPathByName(string $name): string
    {
        return '';
    }
}

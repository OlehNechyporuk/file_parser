<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Domain\Entity\Database;
use App\Domain\Repository\DatabaseRepositoryInterface;

class GetDbListUseCase
{
    private DatabaseRepositoryInterface $repository;

    public function __construct(DatabaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Database[]
     */
    public function __invoke(): array
    {
        return $this->repository->list();
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Job;

interface JobSessionHandlerInterface
{
    public const JOBS_CACHE_KEY = 'JOBS_CACHE';
    public const JOB_CACHE_PREFIX = 'JOB_CACHE_KEY_';
    public const STATUS_WORK = 'work';
    public const STATUS_DONE = 'done';

    public function list(): array;

    public function isExist(string $id): bool;

    public function add(Job $job): void;

    public function delete(string $id): void;
}

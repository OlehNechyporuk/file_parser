<?php

declare(strict_types=1);

namespace App\Domain\Service;

interface ConvertServiceInterface
{
    public function __invoke(string $filename, array $files);
}

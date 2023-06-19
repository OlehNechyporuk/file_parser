<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Domain\Service\ConvertServiceInterface;

class ParseFilesUseCase
{
    private ConvertServiceInterface $convertor;

    public function __construct(ConvertServiceInterface $convertor)
    {
        $this->convertor = $convertor;
    }

    public function __invoke(string $filename, array $files)
    {
        $this->convertor->__invoke($filename, $files);
    }
}

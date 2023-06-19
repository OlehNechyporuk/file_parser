<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class Database
{
    private string $name;
    private string $path;
    private int $size;

    public function __construct(string $name, string $path, int $size)
    {
        $this->name = $name;
        $this->path = $path;
        $this->size = $size;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}

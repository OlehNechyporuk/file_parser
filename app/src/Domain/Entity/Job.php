<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class Job
{
    public function __construct(
        private string $id,
        private array $files,
        private string $type,
        private string $status,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getData(): array
    {
        return [
            'id' => $this->id,
            'files' => $this->files,
            'type' => $this->type,
            'status' => $this->status,
        ];
    }
}

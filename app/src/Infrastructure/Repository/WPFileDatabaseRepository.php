<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Database;
use App\Domain\Repository\DatabaseRepositoryInterface;
use Symfony\Component\Finder\Finder;

class WPFileDatabaseRepository implements DatabaseRepositoryInterface
{
    public const WP_DIR = 'wp';

    private string $uploadsDir;

    private Finder $finder;

    /**
     * @var Database[]
     */
    private array $files = [];

    public function __construct(string $uploadsDir, Finder $finder)
    {
        $this->uploadsDir = $uploadsDir;
        $this->finder = $finder;
    }

    /**
     * @return Database
     */
    public function list(): array
    {
        $this->finder->files()->name('*.sql')->in($this->uploadsDir.DIRECTORY_SEPARATOR.self::WP_DIR.DIRECTORY_SEPARATOR);

        foreach ($this->finder as $file) {
            $this->files[] = new Database($file->getFilename(), $file->getRealPath(), $file->getSize());
        }

        return $this->files;
    }

    public function read(string $path): mixed
    {
        $handle = fopen($path, 'r');

        while (!feof($handle)) {
            yield fgets($handle);
        }

        fclose($handle);
    }

    public function getPathByName(string $name): string
    {
        return $this->uploadsDir.DIRECTORY_SEPARATOR.self::WP_DIR.DIRECTORY_SEPARATOR.$name;
    }
}

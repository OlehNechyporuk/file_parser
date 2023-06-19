<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Repository\DatabaseRepositoryInterface;
use App\Domain\Service\ConvertServiceInterface;
use App\Domain\Service\ParseServiceInterface;

class CSVConvertorService implements ConvertServiceInterface
{
    private DatabaseRepositoryInterface $repository;
    private ParseServiceInterface $service;

    public function __construct(
        DatabaseRepositoryInterface $repository,
        ParseServiceInterface $service
    ) {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function __invoke(string $filename, array $files)
    {
        $handle = fopen('/code/app/public/uploads/wp/result/'.$filename.'.csv', 'w+');

        foreach ($files as $name) {
            $iterator = $this->repository->read($this->repository->getPathByName($name));

            foreach ($iterator as $iteration) {
                if (!empty($iteration)) {
                    $rawPost = $this->service->post($iteration);

                    if (!empty($rawPost)) {
                        $string = $this->service->clear($rawPost);
                        $post = $this->service->getPostFromString($string);

                        if (!empty($post->getTitle())) {
                            fputcsv($handle, $this->service->postToArray($post));
                        }
                    }
                }
            }
        }

        fclose($handle);
    }
}

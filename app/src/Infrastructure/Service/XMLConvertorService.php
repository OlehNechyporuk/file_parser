<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Repository\DatabaseRepositoryInterface;
use App\Domain\Service\ConvertServiceInterface;
use App\Domain\Service\ParseServiceInterface;

class XMLConvertorService implements ConvertServiceInterface
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
        $handle = fopen('/code/app/public/uploads/wp/result/'.$filename.'.xml', 'w+');

        $start_xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.'<posts>';
        fwrite($handle, $start_xml.PHP_EOL);

        foreach ($files as $name) {
            $iterator = $this->repository->read($this->repository->getPathByName($name));

            foreach ($iterator as $iteration) {
                if (!empty($iteration)) {
                    $rawPost = $this->service->post($iteration);

                    if (!empty($rawPost)) {
                        $string = $this->service->clear($rawPost);
                        $post = $this->service->getPostFromString($string);

                        if (!empty($post->getTitle())) {
                            $item = '   <post>'.PHP_EOL.'       <title>'.$post->getTitle().'</title>'.PHP_EOL.'       <content>'.htmlspecialchars($post->getContent()).'</content>'.PHP_EOL.'   </post>';
                            fwrite($handle, $item.PHP_EOL);
                        }
                    }
                }
            }
        }

        $end_xml = '</posts>';
        fwrite($handle, $end_xml.PHP_EOL);

        fclose($handle);
    }
}

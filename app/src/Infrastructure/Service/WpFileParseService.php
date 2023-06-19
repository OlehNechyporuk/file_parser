<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Entity\Post;
use App\Domain\Service\ParseServiceInterface;

class WpFileParseService implements ParseServiceInterface
{
    public const POST_PATTERN = '/\(\d+,\s\d+,\s\'\d\d\d\d-\d\d\-\d\d.*/';

    public function post(string $rawData): string
    {
        preg_match(self::POST_PATTERN, $rawData, $matches);

        if (!empty($matches)) {
            return $matches[0];
        }

        return '';
    }

    public function clear(string $rawData): string
    {
        $regex_pattetns = [
            '/^\(/',
            '/\)[,;]/',
            '/\',/',
            '/\'/',
            '/<a.*">/', //delete link
            '/<\/a>/',  //delete link
            '/<img.*>/', //delete img
        ];

        return preg_replace($regex_pattetns, '', $rawData);
    }

    public function getPostFromString(string $data): Post
    {
        $arr = explode("\t", $data);

        return new Post($arr[5] ?? '', $arr[4] ?? '');
    }

    public function postToArray(Post $post): array
    {
        return [$post->getTitle(), $post->getContent()];
    }
}

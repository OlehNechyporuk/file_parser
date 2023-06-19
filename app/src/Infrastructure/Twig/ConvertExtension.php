<?php

declare(strict_types=1);

namespace App\Infrastructure\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ConvertExtension extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('formatBytes', [$this, 'formatBytes']),
        ];
    }

    /**
     * @param $bytes
     * @param int $precision
     *
     * @return string
     */
    public function formatBytes($bytes, $precision = 2)
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen(strval($bytes)) - 1) / 3);

        return sprintf("%.{$precision}f", $bytes / pow(1024, $factor)).@$size[$factor];
    }
}

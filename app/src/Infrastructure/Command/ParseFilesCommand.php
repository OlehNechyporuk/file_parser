<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Application\Enum\OutputFileType;
use App\Application\UseCase\ParseFilesUseCase;
use App\Domain\Repository\DatabaseRepositoryInterface;
use App\Domain\Service\JobSessionHandlerInterface;
use App\Domain\Service\ParseServiceInterface;
use App\Infrastructure\Repository\WPFileDatabaseRepository;
use App\Infrastructure\Service\CSVConvertorService;
use App\Infrastructure\Service\TXTConvertorService;
use App\Infrastructure\Service\WpFileParseService;
use App\Infrastructure\Service\XMLConvertorService;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseFilesCommand extends Command
{
    protected static $defaultName = 'parse:sql-wp-files';

    private ParseServiceInterface $service;

    private DatabaseRepositoryInterface $repository;

    private AdapterInterface $cache;

    private string $folder;

    public function __construct(
        WpFileParseService $service,
        WPFileDatabaseRepository $repository,
        AdapterInterface $cache,
        string $wpResultDir
        ) {
        $this->cache = $cache;
        $this->service = $service;
        $this->repository = $repository;
        $this->folder = $wpResultDir;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('type', InputArgument::REQUIRED, 'Output file type (csv/xml/txt). Default csv.')
            ->addArgument('filename', InputArgument::REQUIRED, 'Output filename.')
            ->addArgument('files', InputArgument::IS_ARRAY, 'The list name of the files.')

            ->setDescription('Parse WP database sql files.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $files = $input->getArgument('files');
        $type = $input->getArgument('type');
        $filename = $input->getArgument('filename');

        try {
            $v = match ($type) {
                OutputFileType::csv->name => OutputFileType::csv,
                OutputFileType::xml->name => OutputFileType::xml,
                OutputFileType::txt->name => OutputFileType::txt,
            };
        } catch (\Throwable $th) {
            throw new \Exception('Output file type can be csv, xml or txt');
        }

        if (OutputFileType::csv == $v) {
            $convertor = new CSVConvertorService($this->repository, $this->service, $this->folder);
        } elseif (OutputFileType::txt == $v) {
            $convertor = new TXTConvertorService($this->repository, $this->service, $this->folder);
        } elseif (OutputFileType::xml == $v) {
            $convertor = new XMLConvertorService($this->repository, $this->service, $this->folder);
        }

        $useCase = new ParseFilesUseCase($convertor);

        $useCase($filename, $files);

        $this->cache->deleteItem(JobSessionHandlerInterface::JOB_CACHE_PREFIX.$filename);

        $output->writeln('Run console work');

        return 0;
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Message;

use App\Infrastructure\Command\ParseFilesCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ParseNotificationHandler implements MessageHandlerInterface
{
    private Application $application;

    public function __construct(KernelInterface $kernel)
    {
        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);
    }

    public function __invoke(ParseNotification $message)
    {
        $command = $this->application->find(ParseFilesCommand::getDefaultName());

        $input = new ArrayInput([
         'files' => $message->getFiles(),
         'type' => $message->getType(),
         'filename' => $message->getId(),
        ]);

        $output = new ConsoleOutput();

        $command->run($input, $output);

        $output->writeln('Job done!');

        return 0;
    }
}

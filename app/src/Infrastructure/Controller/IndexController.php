<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\UseCase\GetDbListUseCase;
use App\Infrastructure\Message\ParseNotification;
use App\Infrastructure\Repository\WPFileDatabaseRepository;
use App\Infrastructure\Service\JobSessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class IndexController extends AbstractController
{
    public function index(WPFileDatabaseRepository $repository, JobSessionHandler $jobsHandler): Response
    {
        $jobs = $jobsHandler->list();

        $useCase = new GetDbListUseCase($repository);
        $list = $useCase();

        return $this->render('index/index.html.twig', [
            'files' => $list,
            'jobs' => $jobs,
        ]);
    }

    public function add(Request $request, MessageBusInterface $bus, JobSessionHandler $jobsHandler): JsonResponse
    {
        $filename = md5($request->get('files').'.'.$request->get('type').time());

        $job = new ParseNotification(
            $filename,
            explode('_', $request->get('files')),
            $request->get('type'),
            JobSessionHandler::STATUS_WORK,
        );

        $jobsHandler->add($job);

        $bus->dispatch($job);

        return new JsonResponse([
            'status' => 'ok',
            'id' => $filename,
            'files' => $request->get('files'),
            'donwload_url' => '/uploads/wp/result/'.$filename.'.'.$request->get('type'),
            'delete_url' => $this->generateUrl('delete', ['name' => $filename, 'type' => $request->get('type')]),
        ]);
    }

    public function check(JobSessionHandler $jobsHandler): JsonResponse
    {
        $jobs = $jobsHandler->list();

        return $this->json($jobs);
    }

    public function delete(string $name, string $type, JobSessionHandler $jobsHandler): JsonResponse
    {
        $jobsHandler->delete($name);

        $file = '/code/app/public/uploads/wp/result/'.$name.'.'.$type;
        if (file_exists($file)) {
            unlink($file);
        }

        return $this->json(['ok']);
    }
}

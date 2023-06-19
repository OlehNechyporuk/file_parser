<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Entity\Job;
use App\Domain\Service\JobSessionHandlerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JobSessionHandler implements JobSessionHandlerInterface
{
    private SessionInterface $session;

    private AdapterInterface $cache;

    private ?string $jobs_cache;

    public function __construct(SessionInterface $session, AdapterInterface $cache)
    {
        $this->session = $session;
        $this->cache = $cache;
        $this->session->start();
        $this->jobs_cache = $this->session->get(self::JOBS_CACHE_KEY);
    }

    public function list(): array
    {
        if (!empty($this->jobs_cache)) {
            $jobs = unserialize($this->jobs_cache);

            foreach ($jobs as $job) {
                $item = $this->cache->getItem(self::JOB_CACHE_PREFIX.$job->getid());
                if ($item->isHit()) {
                    $jobs[$job->getId()]->setStatus(self::STATUS_WORK);
                } else {
                    $jobs[$job->getId()]->setStatus(self::STATUS_DONE);
                }
            }
        } else {
            $jobs = [];
        }

        return $jobs;
    }

    public function isExist(string $id): bool
    {
        if (!empty($this->jobs_cache)) {
            $jobs = unserialize($this->jobs_cache);

            return key_exists($id, $jobs);
        }

        return false;
    }

    public function add(Job $job): void
    {
        $this->addToSession($job);

        $this->addToCache($job);
    }

    public function delete(string $id): void
    {
        if (!empty($this->jobs_cache)) {
            $jobs = unserialize($this->jobs_cache);

            unset($jobs[$id]);

            $this->session->set(self::JOBS_CACHE_KEY, serialize($jobs));
        }
    }

    private function addToSession(Job $job): void
    {
        if (!empty($this->jobs_cache)) {
            $jobs = unserialize($this->jobs_cache);

            $jobs[$job->getId()] = $job;
        } else {
            $jobs[$job->getId()] = $job;
        }

        $this->session->set(self::JOBS_CACHE_KEY, serialize($jobs));
    }

    private function addToCache(Job $job): void
    {
        $item = $this->cache->getItem(self::JOB_CACHE_PREFIX.$job->getId());

        if (!$item->isHit()) {
            $this->cache->save($item);
        }
    }
}

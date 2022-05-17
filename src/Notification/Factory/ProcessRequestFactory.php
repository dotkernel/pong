<?php

declare(strict_types=1);

namespace Notification\Factory;

use Notification\Jobs\ProcessRequest;
use Psr\Container\ContainerInterface;
use QueueJitsu\Job\JobManager;
use QueueJitsu\Scheduler\Scheduler;

/**
 * Class ProcessRequestFactory
 * @package Notification\Factory
 */
class ProcessRequestFactory
{
    /**
     * @param ContainerInterface $container
     * @return ProcessRequest
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ProcessRequest
    {
        return new ProcessRequest(
            $container->get(JobManager::class),
            $container->get(Scheduler::class)
        );
    }
}

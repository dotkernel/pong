<?php

declare(strict_types=1);

namespace Notification\Factory;

use Notification\Worker\SwooleWorker;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class TeamHandlerFactory
 * @package Workspace\Factory
 */
class SwooleWorkerFactory
{
    /**
     * @param ContainerInterface $container
     * @return SwooleWorker
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SwooleWorker
    {
        return new SwooleWorker(
            $container->get(LoggerInterface::class)
        );
    }
}

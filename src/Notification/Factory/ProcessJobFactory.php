<?php

declare(strict_types=1);

namespace Notification\Factory;

use GuzzleHttp\Client;
use Notification\Jobs\ProcessJob;
use Psr\Container\ContainerInterface;

/**
 * Class TeamHandlerFactory
 * @package Workspace\Factory
 */
class ProcessJobFactory
{
    /**
     * @param ContainerInterface $container
     * @return ProcessJob
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ProcessJob
    {
        $client = new Client();
        $config = $container->get('config')['job'];

        return new ProcessJob(
            $client,
            $config
        );
    }
}

<?php

namespace Dot\Swoole\Factory;

use Dot\Swoole\Command\ProcessWorkerCommand;
use Predis\Client;
use Psr\Container\ContainerInterface;

class ProcessWorkerCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return ProcessWorkerCommand
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ProcessWorkerCommand
    {
        $config = $container->get('config')['redis'];
        $client = new Client($config);

        return new ProcessWorkerCommand($client);
    }
}

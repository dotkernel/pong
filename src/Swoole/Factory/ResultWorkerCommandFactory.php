<?php

namespace Dot\Swoole\Factory;

use Dot\Swoole\Command\ResultWorkerCommand;
use Predis\Client;
use Psr\Container\ContainerInterface;

class ResultWorkerCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return ResultWorkerCommand
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ResultWorkerCommand
    {
        $config = $container->get('config')['redis'];
        $client = new Client($config);

        return new ResultWorkerCommand($client);
    }
}

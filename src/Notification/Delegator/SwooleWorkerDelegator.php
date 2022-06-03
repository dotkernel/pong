<?php

namespace Notification\Delegator;

use Notification\Worker\SwooleWorker;
use Predis\Client;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Swoole\Server as Server;
use Mezzio\Swoole\SwooleEmitter;

class SwooleWorkerDelegator
{
    /** @var Client $client */
    protected Client $client;

    /**
     * @param ContainerInterface $container
     * @param $serviceName
     * @param callable $callback
     * @return Server
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $serviceName, callable $callback): Server
    {
        $server = $callback();
        $logger = $container->get(LoggerInterface::class);
        $redisConfig = $container->get('config')['redis'];
        $this->client = new Client($redisConfig);

        $server->on('task', $container->get(SwooleWorker::class));

        $server->on('connect', function ($server, $fd) {
            echo "Client : {$fd}  Connect.\n";
        });
        $server->on('message', function ($server, $frame) {
            echo "received message: {$frame->data}\n";
        });

        // Register the fu nction for the event `receive`
        $server->on('receive', function ($server, $fd, $from_id, $data) use ($logger) {
            // add raw job here
            $this->client->rpush("queue::todo", [json_encode($data)]);
            $logger->notice("Request with data: " . json_encode($data) . " added to queue.\n");
        });

        // Register the function for the event `close`
        $server->on('close', function ($server, $fd) {
            echo "Client: {$fd} close.\n";
        });

        $server->on('finish', function ($server, $taskId, $data) use ($logger) {
            $logger->notice('Task #{taskId} has finished processing', ['taskId' => $taskId]);
        });

        return $server;
    }
}

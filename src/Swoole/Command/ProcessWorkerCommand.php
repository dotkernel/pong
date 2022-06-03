<?php

declare(strict_types=1);

namespace Dot\Swoole\Command;

use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessWorkerCommand extends Command
{
    protected static $defaultName = 'process';

    /** @var Client $redisClient */
    protected Client $redisClient;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->redisClient = $client;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Process freshly received tasks from redis');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while ($data = $this->redisClient->lpop('queue::todo')) {
            $data  = json_decode($data, true);

            /**
             * do stuff with $data
             */

            $this->redisClient->rpush("queue::processed", [json_encode($data)]);
        }

        return 0;
    }
}

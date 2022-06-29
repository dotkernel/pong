<?php

declare(strict_types=1);

namespace Dot\Swoole\Command;

use GuzzleHttp\Exception\GuzzleException;
use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client as GuzzleClient;

class ProcessWorkerCommand extends Command
{
    protected static $defaultName = 'process';

    /** @var Client $redisClient */
    protected Client $redisClient;

    /** @var GuzzleClient $guzzleClient */
    protected GuzzleClient $guzzleClient;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->redisClient = $client;
        $this->guzzleClient = new GuzzleClient();
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
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while ($data = $this->redisClient->lpop('queue::todo')) {
            $data = json_decode($data, true);

            try {
                $response = $this->guzzleClient->request(
                    $data['request']['method'],
                    $data['request']['url'],
                    [
                        'form_params' => $data['request']['body']
                    ]
                );
            } catch (GuzzleException $exception) {
                $this->callFailUrl($data['request']['failUrl'], $exception->getMessage());
            }

            $this->redisClient->rpush("queue::processed", [json_encode($data)]);
        }

        return 0;
    }

    /**
     * @param string $failUrl
     * @param string $errorMessage
     * @return void
     * @throws GuzzleException
     */
    private function callFailUrl(string $failUrl, string $errorMessage = "")
    {
        $this->guzzleClient->request(
            'POST',
            $failUrl,
            [
                'form_params' => [
                    'errorMessage' => $errorMessage
                ]
            ]
        );
    }
}

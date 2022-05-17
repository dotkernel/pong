<?php

namespace Notification\Jobs;

use GuzzleHttp\Client;

class ProcessJob
{
    /** @var array $args */
    protected array $args;

    /**@var array $config*/
    protected array $config;

    /** @var Client $client */
    protected Client $client;

    /**
     * @param string $args
     * @return void
     */
    public function __invoke(string $args)
    {
        $this->args = json_decode($args, true);
        $this->perform();
    }

    /**
     * @param Client $client
     * @param $config
     */
    public function __construct(Client $client, $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function perform()
    {
        if (!empty($this->args['request']))
        {
            $request = $this->args['request'];
            $response = $this->client->request(
                $request['method'],
                $request['url'],
                [
                    'form_params' => $request['body'],
                    'headers' => $request['headers']
                ]
            );

//            echo $response->getStatusCode(); // 200
//            echo $response->getBody(); // '{"id": 1420053, "name": "guzzle", ...}'
        }
    }
}

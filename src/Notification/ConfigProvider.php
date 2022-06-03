<?php

declare(strict_types=1);

namespace Notification;

use Notification\Factory\QueueLoggerAdapterFactory;
use Notification\Factory\SwooleWorkerFactory;
use Notification\Worker\SwooleWorker;
use Swoole\Server as SwooleServer;
use Psr\Log\LoggerInterface;
use Notification\Delegator\SwooleWorkerDelegator;
use Laminas\Log\PsrLoggerAdapter;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies()
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'delegators' => [
                SwooleServer::class => [
                    SwooleWorkerDelegator::class
                ]
            ],
            'factories' => [
                PsrLoggerAdapter::class => QueueLoggerAdapterFactory::class,
                SwooleWorker::class => SwooleWorkerFactory::class
            ],
            'aliases' => [
                LoggerInterface::class => PsrLoggerAdapter::class
            ]
        ];
    }
}

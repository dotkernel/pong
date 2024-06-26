<?php

use Dot\Cli\Command\DemoCommand;
use Dot\Cli\FileLockerInterface;
use Dot\Swoole\Command\ProcessWorkerCommand;
use Dot\Swoole\Command\ResultWorkerCommand;

/**
 * Documentation: https://docs.laminas.dev/laminas-cli/
 */
return [
    'dot_cli' => [
        'version' => '1.0.0',
        'name' => 'DotKernel CLI',
        'commands' => [
            DemoCommand::getDefaultName() => DemoCommand::class,
            ProcessWorkerCommand::getDefaultName() => ProcessWorkerCommand::class,
            ResultWorkerCommand::getDefaultName() => ResultWorkerCommand::class
        ]
    ],
    FileLockerInterface::class => [
        'enabled' => true,
        'dirPath' => getcwd() . '/data/lock',
    ]
];

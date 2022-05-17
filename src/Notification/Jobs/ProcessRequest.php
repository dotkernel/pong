<?php

namespace Notification\Jobs;

use QueueJitsu\Job\Job;
use QueueJitsu\Job\JobManager;
use QueueJitsu\Scheduler\Scheduler;

class ProcessRequest
{
    protected array $args;

    /**@var JobManager $jobManager */
    protected JobManager $jobManager;

    /** @var Scheduler */
    protected Scheduler $jobScheduler;

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
     * ProcessRequest constructor.
     * @param JobManager $jobManager
     * @param Scheduler $scheduler
     */
    public function __construct(
        JobManager $jobManager,
        Scheduler $scheduler
    ) {
        $this->jobManager = $jobManager;
        $this->jobScheduler = $scheduler;
    }

    /**
     * Add requests in queues.
     * @return void
     */
    public function perform()
    {
        if (!empty($this->args['timestamp'])) {
            $this->setDelayedJobEnqueue($this->args['timestamp']);
        } else {
            $this->setJobEnqueue();
        }
    }

    private function setJobEnqueue()
    {
        $this->jobManager->enqueue(new Job(ProcessJob::class,'push', [json_encode($this->args)]));
    }

    private function setDelayedJobEnqueue(int $delay)
    {
        $this->jobScheduler->enqueueAt(
            $delay,
            new Job(
                ProcessJob::class,
                'push',
                [json_encode($this->args)]
            )
        );
    }
}

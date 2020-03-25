<?php

namespace RoboFinance\LaravelRedisQueueClearService\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Redis\RedisManager;

class LaravelRedisQueueClearCommand extends Command
{
    protected $signature = 'queue:clear';
    protected $description = 'Remove all jobs from specified queue';

    /**
     * Execute the console command.
     *
     * @param RedisManager $redis
     * @return mixed
     */
    public function handle(RedisManager $redis)
    {
        $queue = $this->choice('Queue name?', $this->getQueues());

        $redis->del("queues:$queue");
    }

    private function getQueues()
    {
        $queues = [[]];
        foreach (config('horizon.environments') as $env) {
            foreach ($env as $supervisor) {
                $queues[] = $supervisor['queue'];
            }
        }

        return array_unique(array_merge(...$queues));
    }
}

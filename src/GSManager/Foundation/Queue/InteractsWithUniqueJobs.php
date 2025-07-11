<?php

namespace GSManager\Foundation\Queue;

use GSManager\Bus\UniqueLock;
use GSManager\Contracts\Queue\ShouldBeUnique;
use GSManager\Support\Facades\Context;

trait InteractsWithUniqueJobs
{
    /**
     * Store unique job information in the context in case we can't resolve the job on the queue side.
     *
     * @param  mixed  $job
     * @return void
     */
    public function addUniqueJobInformationToContext($job): void
    {
        if ($job instanceof ShouldBeUnique) {
            Context::addHidden([
                'gsmanager_unique_job_cache_store' => $this->getUniqueJobCacheStore($job),
                'gsmanager_unique_job_key' => UniqueLock::getKey($job),
            ]);
        }
    }

    /**
     * Remove the unique job information from the context.
     *
     * @param  mixed  $job
     * @return void
     */
    public function removeUniqueJobInformationFromContext($job): void
    {
        if ($job instanceof ShouldBeUnique) {
            Context::forgetHidden([
                'gsmanager_unique_job_cache_store',
                'gsmanager_unique_job_key',
            ]);
        }
    }

    /**
     * Determine the cache store used by the unique job to acquire locks.
     *
     * @param  mixed  $job
     * @return string|null
     */
    protected function getUniqueJobCacheStore($job): ?string
    {
        return method_exists($job, 'uniqueVia')
            ? $job->uniqueVia()->getName()
            : config('cache.default');
    }
}

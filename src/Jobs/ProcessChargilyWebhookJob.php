<?php

namespace YasserBenaioua\Chargily\Jobs;

use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;
use YasserBenaioua\Chargily\Exceptions\JobClassDoesNotExist;
use YasserBenaioua\Chargily\Models\ChargilyWebhookCall;

class ProcessChargilyWebhookJob extends ProcessWebhookJob
{
    public ChargilyWebhookCall | WebhookCall $webhookCall;

    public function handle()
    {
        collect(config('chargily.jobs'))
            ->each(function (string $jobClassName) {
                if (! class_exists($jobClassName)) {
                    throw JobClassDoesNotExist::make($jobClassName);
                }
            })
            ->each(fn (string $jobClassName) => dispatch(new $jobClassName($this->webhookCall)));
    }
}

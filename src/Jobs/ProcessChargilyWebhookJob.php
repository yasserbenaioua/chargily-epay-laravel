<?php

namespace YasserBenaioua\Chargily\Jobs;

use YasserBenaioua\Chargily\Models\ChargilyWebhookCall;
use YasserBenaioua\Chargily\Exceptions\JobClassDoesNotExist;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

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

<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Services\MailchimpService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;

class SubscribeUserToMailchimp
{
    private MailchimpService $service;

    /**
     * Create the event listener.
     */
    public function __construct(MailchimpService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        try {
            $this->service->subscribe($event->user->email);
            Log::info("SUBSCRIBE SUCCESS === {$event->user->email}");
        } catch (\Exception $e) {
            $lists = $this->service->getAllLists();

            Log::debug('LISTS  ===' . json_encode($lists));
            Log::debug("SUBSCRIBE FAIL === {$event->user->email}" . $e->getMessage());
        }
    }
}

<?php

namespace App\Observers;

use App\Jobs\SendEventMailJob;
use App\Models\Event;
use Illuminate\Support\Carbon;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     *
     * @param Event $event
     * @return void
     */
    public function created(Event $event)
    {
        dispatch (new SendEventMailJob($event))
            ->delay(Carbon::now()->addHour());

    }

}

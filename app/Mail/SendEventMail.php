<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEventMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SendEventMail
    {
        return $this->markdown('emails.events.template')->with([
            'id' => $this->event->id,
            'name' => $this->event->name,
            'description' => $this->event->description,
        ]);
    }
}

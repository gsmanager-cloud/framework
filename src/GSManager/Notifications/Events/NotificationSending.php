<?php

namespace GSManager\Notifications\Events;

use GSManager\Bus\Queueable;
use GSManager\Queue\SerializesModels;

class NotificationSending
{
    use Queueable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $notifiable  The notifiable entity who received the notification.
     * @param  \GSManager\Notifications\Notification  $notification  The notification instance.
     * @param  string  $channel  The channel name.
     */
    public function __construct(
        public $notifiable,
        public $notification,
        public $channel,
    ) {
    }
}

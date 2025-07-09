<?php

namespace GSManager\Notifications\Channels;

use GSManager\Contracts\Events\Dispatcher;
use GSManager\Notifications\Events\BroadcastNotificationCreated;
use GSManager\Notifications\Messages\BroadcastMessage;
use GSManager\Notifications\Notification;
use RuntimeException;

class BroadcastChannel
{
    /**
     * The event dispatcher.
     *
     * @var \GSManager\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Create a new broadcast channel.
     *
     * @param  \GSManager\Contracts\Events\Dispatcher  $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \GSManager\Notifications\Notification  $notification
     * @return array|null
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $this->getData($notifiable, $notification);

        $event = new BroadcastNotificationCreated(
            $notifiable, $notification, is_array($message) ? $message : $message->data
        );

        if ($message instanceof BroadcastMessage) {
            $event->onConnection($message->connection)
                ->onQueue($message->queue);
        }

        return $this->events->dispatch($event);
    }

    /**
     * Get the data for the notification.
     *
     * @param  mixed  $notifiable
     * @param  \GSManager\Notifications\Notification  $notification
     * @return mixed
     *
     * @throws \RuntimeException
     */
    protected function getData($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toBroadcast')) {
            return $notification->toBroadcast($notifiable);
        }

        if (method_exists($notification, 'toArray')) {
            return $notification->toArray($notifiable);
        }

        throw new RuntimeException('Notification is missing toBroadcast / toArray method.');
    }
}

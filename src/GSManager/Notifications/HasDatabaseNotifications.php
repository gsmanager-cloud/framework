<?php

namespace GSManager\Notifications;

trait HasDatabaseNotifications
{
    /**
     * Get the entity's notifications.
     *
     * @return \GSManager\Database\Eloquent\Relations\MorphMany<DatabaseNotification, $this>
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->latest();
    }

    /**
     * Get the entity's read notifications.
     *
     * @return \GSManager\Database\Query\Builder
     */
    public function readNotifications()
    {
        return $this->notifications()->read();
    }

    /**
     * Get the entity's unread notifications.
     *
     * @return \GSManager\Database\Query\Builder
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }
}

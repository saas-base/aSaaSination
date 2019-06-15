<?php

namespace Core\Traits;

trait HasDatabaseNotifications
{
    /**
     * Get the entity's notifications.
     */
    public function notifications()
    {
        if (env('DB_CONNECTION') === 'mongodb') {
            $notificationClass = \Core\Models\MongoDatabaseNotification::class;
        } else {
            $notificationClass = \Illuminate\Notifications\DatabaseNotification::class;
        }

        return $this->morphMany($notificationClass, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the entity's read notifications.
     */
    public function readNotifications()
    {
        return $this->notifications()
            ->whereNotNull('read_at');
    }

    /**
     * Get the entity's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()
            ->whereNull('read_at');
    }
}

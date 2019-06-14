<?php

namespace Base\Traits;

trait Notifiable
{
    use \Illuminate\Notifications\Notifiable {
        \Illuminate\Notifications\Notifiable::notifications as baseNotificationsMethod;
    }

    public function notifications()
    {
        return $this->baseNotificationsMethod();
    }
}

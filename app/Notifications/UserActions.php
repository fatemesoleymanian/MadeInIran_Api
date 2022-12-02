<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class UserActions extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($action_information)
    {
        $this->action_information = $action_information;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->action_information;
    }
}

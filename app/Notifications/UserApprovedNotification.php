<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class UserApprovedNotification extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Your Account is Approved')
            ->line('Congratulations! Your account has been approved by an admin.')
            ->action('Login', url('/login'));
    }
}

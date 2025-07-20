<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class loginNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Login Successful - Healthhive')
        ->greeting('Hello, ' . $notifiable->name . ',')
        ->line('We are pleased to inform you that your login to Healthhive was successful.')
        ->line('Enjoy our latest services and make the most of your experience with us.')
        ->action('Explore Now', url('https://healthhive.me/'))
        ->line('Thank you for choosing Healthhive. If you have any questions, feel free to contact our support team.')
        ->salutation('Best regards,')
        ->salutation('The Healthhive Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

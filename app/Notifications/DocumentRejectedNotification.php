<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentRejectedNotification extends Notification
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
            ->subject('Document Verification Failed - Immediate Action Required')
            ->greeting('Dear '. $notifiable->name . ',')
            ->line('We regret to inform you that your Document verification has failed due to issues with the document you provided.')
            ->line('Unfortunately, the Document you uploaded has been rejected. To avoid any interruptions to your account, please re-upload a valid Document within the next 10 days.')
            ->line('If we do not receive an updated document within this timeframe, your account will be temporarily suspended for security reasons.')
            ->line('Thank you for your prompt attention to this matter.')
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

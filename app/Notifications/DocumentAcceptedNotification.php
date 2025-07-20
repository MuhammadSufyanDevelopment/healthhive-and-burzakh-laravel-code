<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentAcceptedNotification extends Notification
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
            ->subject('Document Verification Successful - Welcome Aboard!')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('We are pleased to inform you that your document verification has been successfully completed.')
            ->line('Your account is now fully verified, and you have access to all features without any restrictions.')
            ->line('Thank you for providing the required documents and for being a valued member of our platform.')
            ->line('If you have any questions or need further assistance, feel free to reach out to our support team.')
            ->salutation('Best regards,')
            ->salutation('The HealthHive Team');
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

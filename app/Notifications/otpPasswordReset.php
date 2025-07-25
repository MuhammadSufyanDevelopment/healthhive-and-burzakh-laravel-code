<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class otpPasswordReset extends Notification
{
    use Queueable;
    protected $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
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
        ->subject('Password Reset Verification Code')
        ->greeting('Hello, '.$notifiable->name)
        ->line('We received a request to reset your password for your Healthhive account.')
        ->line('To proceed with the password reset, please use the following verification code:')
        ->line('**' . $this->otp . '**')
        ->line('If you did not request a password reset, please ignore this email.')
        ->salutation('Best regards,' . "\n" . 'The Healthhive Team');
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

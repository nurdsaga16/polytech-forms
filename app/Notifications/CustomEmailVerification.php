<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class CustomEmailVerification extends Notification
{
    use Queueable;

    /**
     * Verification URL
     */
    public $verificationUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
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
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Подтверждение электронной почты')
            ->line('Вы получили это письмо, чтобы подтвердить ваш адрес электронной почты.')
            ->action('Подтвердить email', $this->verificationUrl)
            ->line('Срок действия ссылки: 60 минут')
            ->line('Если вы не регистрировались на нашем сайте, проигнорируйте это письмо.');
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

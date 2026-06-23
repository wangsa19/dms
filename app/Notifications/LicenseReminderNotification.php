<?php

namespace App\Notifications;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LicenseReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $license;

    /**
     * Create a new notification instance.
     */
    public function __construct(License $license)
    {
        $this->license = $license;
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
                    ->subject('License Reminder: ' . $this->license->name_id)
                    ->greeting('Hello ' . ($notifiable->employee->name ?? 'User') . ',')
                    ->line('This is a reminder that the license "' . $this->license->name_id . '" is set to expire on ' . $this->license->end_date . '.')
                    ->action('View License', url('/licenses/' . $this->license->id))
                    ->line('Please take the necessary actions to renew or manage this license before it expires.');
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

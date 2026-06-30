<?php

namespace App\Notifications;

use App\Models\DocumentOut;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentReturnReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $documentOut;

    /**
     * Create a new notification instance.
     */
    public function __construct(DocumentOut $documentOut)
    {
        $this->documentOut = $documentOut;
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
        $documentName = $this->documentOut->document->name_id ?? 'Unknown Document';
        $returnTime = \Carbon\Carbon::parse($this->documentOut->return_time)->format('Y-m-d H:i');
        $borrowerName = $this->documentOut->borrower->name ?? 'User';

        $url = $notifiable->hasRole('Admin') ? url('/admin/document-out') : url('/document-out');

        return (new MailMessage)
                    ->subject('Document Return Reminder: ' . $documentName)
                    ->greeting('Hello ' . $borrowerName . ',')
                    ->line('This is a reminder that the document "' . $documentName . '" is due for return on ' . $returnTime . '.')
                    ->action('View Documents Out', $url)
                    ->line('Please return the document on time.');
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

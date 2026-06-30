<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDocumentReturnReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-document-return-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for documents that are due for return today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        $this->info("Checking documents due for return on: " . $today);

        // Find DocumentOuts where status is 'Borrowed' and return_time falls on today
        $dueDocuments = \App\Models\DocumentOut::where('status', 'Borrowed')
            ->whereNotNull('return_time')
            ->whereDate('return_time', $today)
            ->get();

        $count = $dueDocuments->count();
        $this->info("Found {$count} document(s) due today.");

        foreach ($dueDocuments as $docOut) {
            // Find borrower's user account
            $user = $docOut->borrower->user ?? null;
            $documentName = $docOut->document->name_id ?? 'Unknown Document';
            $returnTime = \Carbon\Carbon::parse($docOut->return_time)->format('Y-m-d H:i');

            // 1. In-App Notification
            \App\Models\Notification::create([
                'document_id' => $docOut->document_id,
                'user_id'     => $user ? $user->id : $docOut->created_by, // Fallback to creator if borrower has no user
                'message'     => "Document {$documentName} is due for return today at {$returnTime}.",
                'status'      => 'unread',
            ]);

            $this->line("Created in-app notification for: {$documentName}");

            // 2. Email Notification (if borrower has user account and email)
            if ($user && $user->email) {
                $user->notify(new \App\Notifications\DocumentReturnReminderNotification($docOut));
                $this->line("Sent email notification for: {$documentName} to {$user->email}");
            }
        }
    }
}

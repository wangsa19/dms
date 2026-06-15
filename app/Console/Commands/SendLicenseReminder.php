<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendLicenseReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-license-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        // DEBUG: Print tanggal hari ini
        $this->info("Mengecek license untuk tanggal: " . $today);

        // Cek ada berapa license yang punya reminder_date hari ini
        $count = \App\Models\License::where('reminder_date', $today)->count();
        $this->info("Ditemukan " . $count . " license yang harus di-reminder.");

        $licenses = \App\Models\License::where('reminder_date', $today)->get();

        foreach ($licenses as $license) {
            $user = $license->owner->user ?? null;

            // Buat in-app notification (Pastikan user_id menggunakan ID User, bukan Employee ID)
            \App\Models\Notification::create([
                'license_id' => $license->id,
                'user_id'    => $user ? $user->id : $license->owner_id, // Fallback to owner_id if no user found, though it might be wrong if they differ
                'message'    => "License {$license->name_id} akan kedaluwarsa pada {$license->end_date}.",
                'status'     => 'unread',
            ]);

            // Kirim email notification jika user punya akun email
            if ($user && $user->email) {
                $user->notify(new \App\Notifications\LicenseReminderNotification($license));
                $this->line("Notifikasi email dikirim untuk: " . $license->name_id . " ke " . $user->email);
            }

            $this->line("Notifikasi in-app dibuat untuk: " . $license->name_id);
        }
    }
}

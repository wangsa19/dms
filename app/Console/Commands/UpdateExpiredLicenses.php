<?php

namespace App\Console\Commands;

use App\Models\License;
use Illuminate\Console\Command;

class UpdateExpiredLicenses extends Command
{
    protected $signature = 'app:update-expired-licenses';
    protected $description = 'Update status lisensi menjadi expired jika melewati end_date';

    public function handle()
    {
        $today = now()->toDateString();

        $this->info("Mengecek lisensi yang expired untuk tanggal: " . $today);

        $updatedCount = License::where('end_date', '<', $today)
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);

        $this->info("Berhasil mengupdate status " . $updatedCount . " lisensi menjadi expired.");
    }
}

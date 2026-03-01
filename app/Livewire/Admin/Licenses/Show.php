<?php

namespace App\Livewire\Admin\Licenses;

use Livewire\Component;
use App\Models\License;
use App\Models\LicenseVersion;
use Illuminate\Support\Facades\Storage;

class Show extends Component
{
    public $license;

    public function mount($id)
    {
        $this->license = License::with([
            'documentType',
            'category',
            'field',
            'department',
            'section',
            'actionFrequencyUnit',
            'owner.user', // <--- UBAH DI SINI (tambahkan .user)
            'versions' => function ($query) {
                $query->orderBy('version_number', 'desc');
            }
        ])->findOrFail($id);
    }

    public function downloadVersion($versionId)
    {
        $version = LicenseVersion::where('license_id', $this->license->id)->findOrFail($versionId);

        if (Storage::disk('public')->exists($version->file_path)) {
            return Storage::disk('public')->download($version->file_path, $version->file_name);
        }

        $this->dispatch('show-toast', message: 'File fisik tidak ditemukan di server.', type: 'error');
    }

    public function render()
    {
        return view('livewire.admin.licenses.show');
    }
}

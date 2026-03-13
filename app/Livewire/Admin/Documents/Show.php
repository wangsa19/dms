<?php

namespace App\Livewire\Admin\Documents;

use Livewire\Component;
use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Support\Facades\Storage;

class Show extends Component
{
    public $document;

    public function mount($id)
    {
        $this->document = Document::with([
            'documentType',
            'category',
            'field',
            'department',
            'section',
            'owner.user', // <--- UBAH DI SINI (tambahkan .user)
            'versions' => function ($query) {
                $query->with('uploader')->orderBy('version_number', 'desc');
            }
        ])->findOrFail($id);
    }   

    public function downloadVersion($versionId)
    {
        $version = DocumentVersion::where('document_id', $this->document->id)->findOrFail($versionId);

        // Cek apakah file fisiknya ada di storage
        if (Storage::disk('public')->exists($version->file_path)) {
            // Force download file
            return Storage::disk('public')->download($version->file_path, $version->file_name);
        }

        $this->dispatch('show-toast', message: 'File fisik tidak ditemukan di server.', type: 'error');
    }

    public function render()
    {
        return view('livewire.admin.documents.show');
    }
}

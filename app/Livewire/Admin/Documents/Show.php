<?php

namespace App\Livewire\Admin\Documents;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class Show extends Component
{
    use WithFileUploads;

    public $document;
    
    // Properti untuk upload versi baru
    public $newFile;
    public $newRevisionNotes;

    protected $rules = [
        'newFile' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        'newRevisionNotes' => 'nullable|string|max:500',
    ];

    public function mount($document)
    {
        $this->loadDocument($document);
    }

    public function loadDocument($id)
    {
        $this->document = Document::with([
            'documentType',
            'category',
            'field',
            'department',
            'section',
            'owner.user',
            'rack',
            'versions' => function ($query) {
                $query->with('uploader')->orderBy('version_number', 'desc');
            }
        ])->findOrFail($id);
    }

    public function downloadVersion($versionId)
    {
        $version = DocumentVersion::where('document_id', $this->document->id)->findOrFail($versionId);

        if (Storage::disk('public')->exists($version->file_path)) {
            return Storage::disk('public')->download($version->file_path, $version->file_name);
        }

        $this->dispatch('show-toast', message: 'File fisik tidak ditemukan di server.', type: 'error');
    }

    public function uploadVersion()
    {
        Gate::authorize('update', $this->document);

        $this->validate();

        try {
            $latestVersion = DocumentVersion::where('document_id', $this->document->id)->max('version_number');
            $versionNumber = $latestVersion ? ($latestVersion + 1) : 1;

            $filePath = $this->newFile->store('documents', 'public');

            DocumentVersion::create([
                'document_id'    => $this->document->id,
                'version_number' => $versionNumber,
                'file_name'      => $this->newFile->getClientOriginalName(),
                'file_path'      => $filePath,
                'file_type'      => $this->newFile->getClientOriginalExtension(),
                'file_size'      => $this->newFile->getSize(),
                'revision_notes' => $this->newRevisionNotes ?: 'Uploaded new version',
                'uploader_id'    => auth()->id(),
            ]);

            $this->document->update(['current_version' => $versionNumber]);

            $this->dispatch('show-toast', message: 'New version uploaded successfully.', type: 'success');
            
            // Reset form
            $this->reset(['newFile', 'newRevisionNotes']);
            
            // Refresh data
            $this->loadDocument($this->document->id);
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.admin.documents.show');
    }
}


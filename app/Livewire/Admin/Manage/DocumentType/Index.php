<?php

namespace App\Livewire\Admin\Manage\DocumentType;

use Livewire\Component;
use App\Models\DocumentType;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $documentTypeId;
    public $name;
    public $code;

    // Modal state
    public $isOpen = false;

    // Properti untuk tabel
    public $search = '';
    public $perPage = 10;

    // Properti untuk modal delete
    public $showDeleteModal = false;
    public $documentTypeIdToDelete = null;

    /**
     * Tentukan aturan validasi.
     */
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('document_types')->ignore($this->documentTypeId),
            ],
            'code' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Render komponen.
     */
    public function render()
    {
        $documentTypes = DocumentType::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.document-type.index', [
            'documentTypes' => $documentTypes,
        ]);
    }

    /**
     * Buka modal (untuk create atau edit).
     */
    public function openModal()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->isOpen = true;
    }

    /**
     * Tutup modal.
     */
    public function closeModal()
    {
        $this->isOpen = false;
    }

    /**
     * Reset form fields.
     */
    public function resetForm()
    {
        $this->reset(['documentTypeId', 'name', 'code']);
    }

    // --- Logika CRUD ---

    /**
     * Tampilkan modal untuk membuat document type baru.
     */
    public function create()
    {
        $this->openModal();
    }

    /**
     * Tampilkan modal untuk mengedit document type yang ada.
     */
    public function edit($id)
    {
        try {
            $documentType = DocumentType::findOrFail($id);
            $this->documentTypeId = $id;
            $this->name = $documentType->name;
            $this->code = $documentType->code;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Document Type not found.', type: 'error');
        }
    }

    /**
     * Simpan data (baik baru atau update).
     */
    public function save()
    {
        $data = $this->validate();

        // Convert empty string code to null
        $data['code'] = $data['code'] === "" ? null : $data['code'];

        try {
            DocumentType::updateOrCreate(['id' => $this->documentTypeId], $data);

            $message = $this->documentTypeId ? 'Document Type updated successfully.' : 'Document Type created successfully.';
            $this->dispatch('show-toast', message: $message, type: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Tampilkan modal konfirmasi delete.
     */
    public function confirmDelete($id)
    {
        $this->documentTypeIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Hapus data document type.
     */
    public function delete()
    {
        if ($this->documentTypeIdToDelete) {
            try {
                $documentType = DocumentType::find($this->documentTypeIdToDelete);
                if ($documentType) {
                    $documentType->delete();
                    $this->dispatch('show-toast', message: 'Document Type deleted successfully.', type: 'success');
                } else {
                    $this->dispatch('show-toast', message: 'Document Type not found.', type: 'error');
                }
            } catch (\Exception $e) {
                // Tangani error jika data terkait dengan data lain
                $this->dispatch('show-toast', message: 'Could not delete document type. It might be in use.', type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->documentTypeIdToDelete = null;
    }

    // --- Helper untuk Live Search & Pagination ---

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }
}

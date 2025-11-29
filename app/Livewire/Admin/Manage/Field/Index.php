<?php

namespace App\Livewire\Admin\Manage\Field;

use Livewire\Component;
use App\Models\Field; 
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $fieldId;
    public $name;
    public $code;

    // Modal state
    public $isOpen = false;

    // Properti untuk tabel
    public $search = '';
    public $perPage = 10;

    // Properti untuk modal delete
    public $showDeleteModal = false;
    public $fieldIdToDelete = null;

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
                Rule::unique('fields')->ignore($this->fieldId),
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
        $fields = Field::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.field.index', [
            'fields' => $fields,
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
        $this->reset(['fieldId', 'name', 'code']);
    }

    // --- Logika CRUD ---

    /**
     * Tampilkan modal untuk membuat field baru.
     */
    public function create()
    {
        $this->openModal();
    }

    /**
     * Tampilkan modal untuk mengedit field yang ada.
     */
    public function edit($id)
    {
        try {
            $field = Field::findOrFail($id);
            $this->fieldId = $id;
            $this->name = $field->name;
            $this->code = $field->code;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Field not found.', type: 'error');
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
            Field::updateOrCreate(['id' => $this->fieldId], $data);

            $message = $this->fieldId ? 'Field updated successfully.' : 'Field created successfully.';
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
        $this->fieldIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Hapus data field.
     */
    public function delete()
    {
        if ($this->fieldIdToDelete) {
            try {
                $field = Field::find($this->fieldIdToDelete);
                if ($field) {
                    $field->delete();
                    $this->dispatch('show-toast', message: 'Field deleted successfully.', type: 'success');
                } else {
                    $this->dispatch('show-toast', message: 'Field not found.', type: 'error');
                }
            } catch (\Exception $e) {
                // Tangani error jika data terkait dengan data lain
                $this->dispatch('show-toast', message: 'Could not delete field. It might be in use.', type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->fieldIdToDelete = null;
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

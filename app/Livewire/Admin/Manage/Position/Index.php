<?php

namespace App\Livewire\Admin\Manage\Position;

use Livewire\Component;
use App\Models\Position;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $positionId;
    public $name;
    public $code;

    // Modal state
    public $isOpen = false;

    // Properti untuk tabel
    public $search = '';
    public $perPage = 10;

    // Properti untuk modal delete
    public $showDeleteModal = false;
    public $positionIdToDelete = null;

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
                Rule::unique('positions')->ignore($this->positionId),
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
        $positions = Position::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.position.index', [
            'positions' => $positions,
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
        $this->reset(['positionId', 'name', 'code']);
    }

    // --- Logika CRUD ---

    /**
     * Tampilkan modal untuk membuat posisi baru.
     */
    public function create()
    {
        $this->openModal();
    }

    /**
     * Tampilkan modal untuk mengedit posisi yang ada.
     */
    public function edit($id)
    {
        try {
            $position = Position::findOrFail($id);
            $this->positionId = $id;
            $this->name = $position->name;
            $this->code = $position->code;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Position not found.', type: 'error');
        }
    }

    /**
     * Simpan data (baik baru atau update).
     */
    public function save()
    {
        $data = $this->validate();

        $data['code'] = $data['code'] === "" ? null : $data['code'];

        try {
            Position::updateOrCreate(['id' => $this->positionId], $data);

            $message = $this->positionId ? 'Position updated successfully.' : 'Position created successfully.';
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
        $this->positionIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Hapus data posisi.
     */
    public function delete()
    {
        if ($this->positionIdToDelete) {
            try {
                $position = Position::find($this->positionIdToDelete);
                if ($position) {
                    $position->delete();
                    $this->dispatch('show-toast', message: 'Position deleted successfully.', type: 'success');
                } else {
                    $this->dispatch('show-toast', message: 'Position not found.', type: 'error');
                }
            } catch (\Exception $e) {
                // Tangani error, misalnya jika posisi terkait dengan data lain
                $this->dispatch('show-toast', message: 'Could not delete position. It might be in use.', type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->positionIdToDelete = null;
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

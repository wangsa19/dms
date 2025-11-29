<?php

namespace App\Livewire\Admin\Manage\ActionFrequencyUnit;

use Livewire\Component;
use App\Models\ActionFrequencyUnit; 
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $unitId;
    public $name;
    public $code;

    // Modal state
    public $isOpen = false;

    // Properti untuk tabel
    public $search = '';
    public $perPage = 10;

    // Properti untuk modal delete
    public $showDeleteModal = false;
    public $unitIdToDelete = null;

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
                Rule::unique('action_frequency_units')->ignore($this->unitId),
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
        $actionFrequencyUnits = ActionFrequencyUnit::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.action-frequency-unit.index', [
            'actionFrequencyUnits' => $actionFrequencyUnits,
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
        $this->reset(['unitId', 'name', 'code']);
    }

    // --- Logika CRUD ---

    /**
     * Tampilkan modal untuk membuat unit baru.
     */
    public function create()
    {
        $this->openModal();
    }

    /**
     * Tampilkan modal untuk mengedit unit yang ada.
     */
    public function edit($id)
    {
        try {
            $unit = ActionFrequencyUnit::findOrFail($id);
            $this->unitId = $id;
            $this->name = $unit->name;
            $this->code = $unit->code;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Unit not found.', type: 'error');
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
            ActionFrequencyUnit::updateOrCreate(['id' => $this->unitId], $data);

            $message = $this->unitId ? 'Action Frequency Unit updated successfully.' : 'Action Frequency Unit created successfully.';
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
        $this->unitIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Hapus data unit.
     */
    public function delete()
    {
        if ($this->unitIdToDelete) {
            try {
                $unit = ActionFrequencyUnit::find($this->unitIdToDelete);
                if ($unit) {
                    $unit->delete();
                    $this->dispatch('show-toast', message: 'Action Frequency Unit deleted successfully.', type: 'success');
                } else {
                    $this->dispatch('show-toast', message: 'Unit not found.', type: 'error');
                }
            } catch (\Exception $e) {
                // Tangani error jika data terkait dengan data lain
                $this->dispatch('show-toast', message: 'Could not delete unit. It might be in use.', type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->unitIdToDelete = null;
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

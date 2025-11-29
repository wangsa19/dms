<?php

namespace App\Livewire\Admin\Manage\Department;

use Livewire\Component;
use App\Models\Department;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $departmentId;
    public $name;
    public $code;

    // Modal state
    public $isOpen = false;

    // Properti untuk tabel
    public $search = '';
    public $perPage = 10;

    // Properti untuk modal delete
    public $showDeleteModal = false;
    public $departmentIdToDelete = null;

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
                Rule::unique('departments')->ignore($this->departmentId),
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
        $departments = Department::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.department.index', [
            'departments' => $departments,
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
        $this->reset(['departmentId', 'name', 'code']);
    }

    // --- Logika CRUD ---

    /**
     * Tampilkan modal untuk membuat departemen baru.
     */
    public function create()
    {
        $this->openModal();
    }

    /**
     * Tampilkan modal untuk mengedit departemen yang ada.
     */
    public function edit($id)
    {
        try {
            $department = Department::findOrFail($id);
            $this->departmentId = $id;
            $this->name = $department->name;
            $this->code = $department->code;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Department not found.', type: 'error');
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
            Department::updateOrCreate(['id' => $this->departmentId], $data);

            $message = $this->departmentId ? 'Department updated successfully.' : 'Department created successfully.';
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
        $this->departmentIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Hapus data departemen.
     */
    public function delete()
    {
        if ($this->departmentIdToDelete) {
            try {
                $department = Department::find($this->departmentIdToDelete);
                if ($department) {
                    $department->delete();
                    $this->dispatch('show-toast', message: 'Department deleted successfully.', type: 'success');
                } else {
                    $this->dispatch('show-toast', message: 'Department not found.', type: 'error');
                }
            } catch (\Exception $e) {
                // Tangani error, misalnya jika departemen terkait dengan data lain (karyawan, dll)
                $this->dispatch('show-toast', message: 'Could not delete department. It might be in use.', type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->departmentIdToDelete = null;
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

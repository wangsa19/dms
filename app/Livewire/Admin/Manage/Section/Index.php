<?php

namespace App\Livewire\Admin\Manage\Section;

use Livewire\Component;
use App\Models\Section;
use App\Models\Department;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $sectionId;
    public $department_id;
    public $name;
    public $code;

    // Modal state
    public $isOpen = false;

    // Properti untuk tabel
    public $search = '';
    public $perPage = 10;

    // Properti untuk modal delete
    public $showDeleteModal = false;
    public $sectionIdToDelete = null;

    /**
     * Tentukan aturan validasi.
     */
    protected function rules()
    {
        return [
            'department_id' => [
                'required',
                'exists:departments,id' 
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sections')->ignore($this->sectionId),
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
        // Query Section dengan relasi Department
        $sections = Section::with('department') // Eager load department untuk menghindari N+1 query
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('department', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate($this->perPage);

        // Ambil data department untuk dropdown di modal
        $departments = Department::orderBy('name', 'asc')->get();

        return view('livewire.admin.manage.section.index', [
            'sections' => $sections,
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
        $this->reset(['sectionId', 'department_id', 'name', 'code']);
    }

    // --- Logika CRUD ---

    /**
     * Tampilkan modal untuk membuat section baru.
     */
    public function create()
    {
        $this->openModal();
    }

    /**
     * Tampilkan modal untuk mengedit section yang ada.
     */
    public function edit($id)
    {
        try {
            $section = Section::findOrFail($id);
            $this->sectionId = $id;
            $this->department_id = $section->department_id; // Load existing department
            $this->name = $section->name;
            $this->code = $section->code;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Section not found.', type: 'error');
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
            Section::updateOrCreate(['id' => $this->sectionId], $data);

            $message = $this->sectionId ? 'Section updated successfully.' : 'Section created successfully.';
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
        $this->sectionIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Hapus data section.
     */
    public function delete()
    {
        if ($this->sectionIdToDelete) {
            try {
                $section = Section::find($this->sectionIdToDelete);
                if ($section) {
                    $section->delete();
                    $this->dispatch('show-toast', message: 'Section deleted successfully.', type: 'success');
                } else {
                    $this->dispatch('show-toast', message: 'Section not found.', type: 'error');
                }
            } catch (\Exception $e) {
                // Tangani error jika data terkait dengan data lain
                $this->dispatch('show-toast', message: 'Could not delete section. It might be in use.', type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->sectionIdToDelete = null;
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

<?php

namespace App\Livewire\Admin\Manage\Permission;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Properti untuk form
    public $permissionId;
    public $name;

    // Properti untuk modal
    public $isOpen = false;
    public $showDeleteModal = false;
    public $permissionIdToDelete = null;

    // Properti untuk tabel
    public $search = '';
    public $perPage = 10;

    // Validasi
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('permissions', 'name')
                    ->where(function ($query) {
                        return $query->where('guard_name', 'web');
                    })
                    ->ignore($this->permissionId),
            ],
        ];
    }

    public function render()
    {
        $permissions = Permission::where('guard_name', 'web')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.permission.index', [
            'permissions' => $permissions,
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetForm()
    {
        $this->reset(['permissionId', 'name']);
    }

    // --- Logika CRUD ---

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permissionId = $id;
        $this->name = $permission->name;

        $this->resetErrorBag();
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate();

        Permission::updateOrCreate(
            ['id' => $this->permissionId],
            [
                'name' => $this->name,
                'guard_name' => 'web'
            ]
        );

        $message = $this->permissionId ? 'Permission updated successfully.' : 'Permission created successfully.';
        $this->dispatch('show-toast', message: $message, type: 'success');

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->permissionIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->permissionIdToDelete) {
            $permission = Permission::find($this->permissionIdToDelete);
            if ($permission) {
                // Tidak perlu cek 'super_admin' karena ini permission
                $permission->delete();
                $this->dispatch('show-toast', message: 'Permission deleted successfully.', type: 'success');
            }
        }

        $this->showDeleteModal = false;
        $this->permissionIdToDelete = null;
    }

    // --- Helper untuk Live Search & Pagination ---

    public function updatedSearch()
    {
        $this->resetPage(); // Reset pagination saat ada search baru
    }

    public function updatedPerPage()
    {
        $this->resetPage(); // Reset pagination saat ganti perPage
    }
}

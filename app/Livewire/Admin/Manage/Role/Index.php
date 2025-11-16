<?php

namespace App\Livewire\Admin\Manage\Role;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Properti untuk form
    public $roleId;
    public $name;
    public $selectedPermissions = []; 

    // Properti untuk modal
    public $isOpen = false;
    public $showDeleteModal = false;
    public $roleIdToDelete = null;

    // ProBperti untuk tabel
    public $search = '';
    public $perPage = 10;

    public $allPermissions = [];

    // Validasi
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('roles', 'name')->ignore($this->roleId),
            ],
            'selectedPermissions' => 'nullable|array',
            'selectedPermissions.*' => 'exists:permissions,id',
        ];
    }

    public function mount()
    {
        $this->allPermissions = Permission::all();
    }

    public function render()
    {
        $roles = Role::with('permissions')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.role.index', [
            'roles' => $roles,
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
        $this->reset(['roleId', 'name', 'selectedPermissions']);
    }

    // --- Logika CRUD ---

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $id;
        $this->name = $role->name;

        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();

        $this->resetErrorBag();
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate();

        $role = Role::updateOrCreate(
            ['id' => $this->roleId],
            ['name' => $this->name, 'guard_name' => 'web'] 
        );

        $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

        $role->syncPermissions($permissions);

        $message = $this->roleId ? 'Role updated successfully.' : 'Role created successfully.';
        $this->dispatch('show-toast', message: $message, type: 'success');

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->roleIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->roleIdToDelete) {
            $role = Role::find($this->roleIdToDelete);
            if ($role && $role->name !== 'super_admin') {
                $role->delete();
                $this->dispatch('show-toast', message: 'Role deleted successfully.', type: 'success');
            } else {
                $this->dispatch('show-toast', message: 'Cannot delete super_admin role.', type: 'error');
            }
        }

        $this->showDeleteModal = false;
        $this->roleIdToDelete = null;
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

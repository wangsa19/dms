<?php

namespace App\Livewire\Admin\Manage\User;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Employee;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $userId;
    public $name;
    public $email;
    public $password;
    public $employee_id;
    public $selectedRoles = []; 
    public $isOpen = false; 

    // Properti untuk tabel
    public $search = '';
    public $perPage = 10;

    // Properti untuk modal delete
    public $showDeleteModal = false;
    public $userIdToDelete = null;

    // Ambil data untuk dropdown
    public $allRoles = [];
    public $allEmployees = [];

    public function mount()
    {
        $this->allRoles = Role::all();
        $this->allEmployees = Employee::all();
    }

    protected function rules()
    {
        $passwordRules = 'nullable|min:6';
        if (!$this->userId) {
            $passwordRules = 'required|min:6';
        }

        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'employee_id' => 'nullable|exists:employees,id',
            'password' => $passwordRules,
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,id',
        ];
    }

    public function render()
    {
        return view('livewire.admin.manage.user.index', [
            'users' => User::with(['employee', 'roles']) 
                ->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate($this->perPage),
        ]);
    }

    // --- Kontrol Modal ---

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
        $this->reset(['userId', 'name', 'email', 'password', 'employee_id', 'selectedRoles']);
    }

    // --- Logika CRUD ---

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->employee_id = $user->employee_id;

        $this->selectedRoles = $user->roles->pluck('id')->toArray();

        $this->resetErrorBag();
        $this->isOpen = true;
    }

    public function save()
    {
        $data = $this->validate();

        $employeeId = $data['employee_id'] === "" ? null : $data['employee_id'];

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'employee_id' => $employeeId,
        ];

        if (!empty($data['password'])) {
            $userData['password'] = bcrypt($data['password']);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $userData);

        $roles = Role::whereIn('id', $this->selectedRoles)->get();

        $user->syncRoles($roles); 

        $message = $this->userId ? 'User updated successfully.' : 'User created successfully.';
        $this->dispatch('show-toast', message: $message, type: 'success');
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->userIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->userIdToDelete) {
            User::find($this->userIdToDelete)->delete();
            $this->dispatch('show-toast', message: 'User deleted successfully.', type: 'success');
        }
        $this->showDeleteModal = false;
        $this->userIdToDelete = null;
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

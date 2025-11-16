<?php

namespace App\Livewire\Admin\Manage\User;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Employee;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $userId;
    public $name;
    public $email;
    public $password;
    public $employee_id;

    // --- PERUBAHAN 1: Diubah ke singular untuk 'satu user satu role' ---
    public $selectedRole = null;

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
        $this->allRoles = Role::where('guard_name', 'web')->get();
        $this->allEmployees = Employee::all();
    }

    protected function rules()
    {
        $passwordRules = 'nullable|min:6';
        if (!$this->userId) {
            $passwordRules = 'required|min:6';
        }

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->userId),
            ],
            'employee_id' => 'nullable|exists:employees,id',
            'password' => $passwordRules,

            // --- PERUBAHAN 2: Validasi diubah ke singular ---
            'selectedRole' => 'required|exists:roles,id',
        ];
    }

    public function render()
    {
        $users = User::with(['employee', 'roles'])
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.user.index', [
            'users' => $users,
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
        // --- PERUBAHAN 3: 'selectedRoles' diubah ke 'selectedRole' ---
        $this->reset(['userId', 'name', 'email', 'password', 'employee_id', 'selectedRole']);
    }

    // --- Logika CRUD ---

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->employee_id = $user->employee_id;
        $this->password = null;

        // --- PERUBAHAN 4: Ambil 1 role saja ---
        // Dapatkan ID dari role pertama yang dimiliki user, atau null jika tidak ada
        $this->selectedRole = $user->roles->first()?->id;

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
            $userData['password'] = Hash::make($data['password']);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $userData);

        // --- PERUBAHAN 5 (SOLUSI ERROR): ---
        // 1. Temukan role berdasarkan ID yang dipilih dari $this->selectedRole
        $role = Role::findById($this->selectedRole);

        // 2. Gunakan syncRoles dengan NAMA role. Ini akan memperbaiki error.
        //    syncRoles() akan otomatis menghapus role lama dan menambah role baru.
        if ($role) {
            $user->syncRoles($role->name);
        }

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
            $user = User::find($this->userIdToDelete);
            if ($user) {
                $user->delete();
                $this->dispatch('show-toast', message: 'User deleted successfully.', type: 'success');
            }
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

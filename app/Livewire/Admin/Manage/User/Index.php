<?php

namespace App\Livewire\Admin\Manage\User;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
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
    public $role_id;
    public $employee_id;

    public $isOpen = false; // Modal toggle

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'role_id' => 'nullable|exists:roles,id',
        'employee_id' => 'nullable|exists:employees,id',
        'password' => 'nullable|min:6',
    ];

    public function render()
    {
        return view('livewire.admin.manage.user.index', [
            'users' => User::with(['employee', 'role'])->latest()->paginate(10),
            'roles' => Role::all(),
            'employees' => Employee::all(),
        ]);
    }

    // CRUD
    public function openModal()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetForm()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'role_id', 'employee_id']);
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->employee_id = $user->employee_id;

        $this->isOpen = true;
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        } else {
            unset($data['password']);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        $this->closeModal();
        session()->flash('message', 'User saved successfully!');
    }

    public $showDeleteModal = false;
    public $userIdToDelete = null;

    public function confirmDelete($id)
    {
        $this->userIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        User::find($this->userIdToDelete)->delete();
        $this->showDeleteModal = false;
        $this->userIdToDelete = null;
        session()->flash('message', 'User deleted successfully.');
    }
}

<?php

namespace App\Livewire\Admin\Manage\Employee;

use App\Exports\EmployeeExport;
use App\Imports\EmployeeImport;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Section;
use App\Models\Position;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Form fields Employee
    public $employeeId;
    public $nik;
    public $name;
    public $gender;
    public $phone;
    public $department_id;
    public $section_id;
    public $position_id;

    // Form fields User Account
    public $userId = null;
    public $email;
    public $password;
    public $role_name;

    // Data lists for dropdowns
    public $departments = [];
    public $sections = [];
    public $positions = [];
    public $roles = [];

    // Modal state
    public $isOpen = false;

    // Table properties
    public $search = '';
    public $perPage = 10;

    // Delete modal properties
    public $showDeleteModal = false;
    public $employeeIdToDelete = null;
    public $employeeNameToDelete = '';

    // Property buat file import
    public $fileImport;
    public $isImportModalOpen = false;

    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        $this->positions = Position::orderBy('name')->get();
        $this->roles = Role::orderBy('name')->get(); // Load data roles dari Spatie
    }

    public function updatedDepartmentId($value)
    {
        if ($value) {
            $this->sections = Section::where('department_id', $value)->orderBy('name')->get();
        } else {
            $this->sections = [];
        }
        $this->section_id = null;
    }

    protected function rules()
    {
        return [
            // Rules Employee
            'nik' => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees')->ignore($this->employeeId)
            ],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'phone' => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'position_id' => ['nullable', 'exists:positions,id'],

            // Rules User Account (Dinilai dari input Email)
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            // Role wajib diisi KALAU email diisi
            'role_name' => [
                Rule::requiredIf(fn() => !empty($this->email)),
                'nullable',
                'exists:roles,name'
            ],
            // Password wajib KALAU email diisi DAN ini akun baru
            'password' => [
                Rule::requiredIf(fn() => !empty($this->email) && empty($this->userId)),
                'nullable',
                'min:6'
            ],
        ];
    }

    public function render()
    {
        // Load data employee sekaligus relasi usernya untuk indikator
        $employees = Employee::with(['department', 'section', 'position', 'user'])
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nik', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.employee.index', [
            'employees' => $employees,
        ]);
    }

    public function openModal()
    {
        $this->resetErrorBag();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'employeeId',
            'nik',
            'name',
            'gender',
            'phone',
            'department_id',
            'section_id',
            'position_id',
            'userId',
            'email',
            'password',
            'role_name'
        ]);
        $this->sections = [];
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit($id)
    {
        try {
            $employee = Employee::with('user')->findOrFail($id);

            $this->employeeId = $id;
            $this->nik = $employee->nik;
            $this->name = $employee->name;
            $this->gender = $employee->gender;
            $this->phone = $employee->phone;
            $this->department_id = $employee->department_id;
            $this->position_id = $employee->position_id;

            $this->updatedDepartmentId($this->department_id);
            $this->section_id = $employee->section_id;

            // Jika punya akun user, isi juga form akunnya
            if ($employee->user) {
                $this->userId = $employee->user->id;
                $this->email = $employee->user->email;
                $this->role_name = $employee->user->roles->first()?->name;
            }

            $this->openModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Employee not found.', type: 'error');
        }
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $employee = Employee::updateOrCreate(
                ['id' => $this->employeeId],
                [
                    'nik' => $this->nik,
                    'name' => $this->name,
                    'gender' => $this->gender,
                    'phone' => $this->phone,
                    'department_id' => $this->department_id,
                    'section_id' => $this->section_id,
                    'position_id' => $this->position_id,
                ]
            );

            // Cek apakah HRD berniat bikin/update akun (dilihat dari field email)
            if (!empty($this->email)) {
                $userData = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'employee_id' => $employee->id,
                ];

                if (!empty($this->password)) {
                    $userData['password'] = Hash::make($this->password);
                }

                $user = User::updateOrCreate(
                    ['id' => $this->userId],
                    $userData
                );

                if ($this->role_name) {
                    $user->syncRoles([$this->role_name]);
                }
            }
            // Jika email dikosongkan (dihapus manual) saat edit, artinya akses dicabut
            elseif ($this->userId) {
                User::find($this->userId)?->delete();
                $this->userId = null;
            }

            DB::commit();

            $message = $this->employeeId ? 'Employee updated successfully.' : 'Employee created successfully.';
            $this->dispatch('show-toast', message: $message, type: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function confirmDelete($id)
    {
        $employee = Employee::find($id);
        if ($employee) {
            $this->employeeIdToDelete = $id;
            $this->employeeNameToDelete = $employee->name;
            $this->showDeleteModal = true;
        }
    }

    public function delete()
    {
        if ($this->employeeIdToDelete) {
            try {
                $employee = Employee::find($this->employeeIdToDelete);
                if ($employee) {
                    $employee->delete();
                    $this->dispatch('show-toast', message: 'Employee deleted successfully.', type: 'success');
                } else {
                    $this->dispatch('show-toast', message: 'Employee not found.', type: 'error');
                }
            } catch (\Exception $e) {
                $this->dispatch('show-toast', message: 'Could not delete employee. It might be linked to other records.', type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->employeeIdToDelete = null;
        $this->employeeNameToDelete = '';
    }

    // --- Helpers ---

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // --- Logic Import ---

    public function openImportModal()
    {
        $this->resetErrorBag();
        $this->fileImport = null;
        $this->isImportModalOpen = true;
    }

    public function closeImportModal()
    {
        $this->isImportModalOpen = false;
        $this->fileImport = null;
    }

    public function importExcel()
    {
        $this->validate([
            'fileImport' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new EmployeeImport, $this->fileImport);

            $this->dispatch('show-toast', message: 'Data imported successfully!', type: 'success');
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error importing: ' . $e->getMessage(), type: 'error');
        }
    }

    public function export()
    {
        return Excel::download(new EmployeeExport, 'employee.xlsx');
    }
}

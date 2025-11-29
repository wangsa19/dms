<?php

namespace App\Livewire\Admin\Manage\Employee;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Section;
use App\Models\Position;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $employeeId;
    public $nik;
    public $name;
    public $gender;
    public $phone;
    public $department_id;
    public $section_id;
    public $position_id;

    // Data lists for dropdowns
    public $departments = [];
    public $sections = [];
    public $positions = [];

    // Modal state
    public $isOpen = false;

    // Table properties
    public $search = '';
    public $perPage = 10;

    // Delete modal properties
    public $showDeleteModal = false;
    public $employeeIdToDelete = null;
    public $employeeNameToDelete = '';

    /**
     * Mount: Load initial data for dropdowns (Departments & Positions)
     */
    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        $this->positions = Position::orderBy('name')->get();
    }

    /**
     * Updated Hook: When Department changes, filter Sections.
     * Pastikan Model Section punya kolom 'department_id' jika ingin filtering.
     * Jika tidak ada hubungan, baris filtering bisa dihapus.
     */
    public function updatedDepartmentId($value)
    {
        if ($value) {
            // Asumsi: Section memiliki relation atau foreign key ke Department
            // Jika di tabel sections ada 'department_id', gunakan ini:
            $this->sections = Section::where('department_id', $value)->orderBy('name')->get();

            // Jika tidak ada relasi langsung, load semua section:
            // $this->sections = Section::orderBy('name')->get();
        } else {
            $this->sections = [];
        }
        // Reset section selection
        $this->section_id = null;
    }

    /**
     * Validation Rules
     */
    protected function rules()
    {
        return [
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
        ];
    }

    /**
     * Render the component
     */
    public function render()
    {
        $employees = Employee::with(['department', 'section', 'position'])
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

    /**
     * Open Modal (Create/Edit)
     */
    public function openModal()
    {
        $this->resetErrorBag();
        $this->isOpen = true;
    }

    /**
     * Close Modal
     */
    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    /**
     * Reset Form Fields
     */
    public function resetForm()
    {
        $this->reset(['employeeId', 'nik', 'name', 'gender', 'phone', 'department_id', 'section_id', 'position_id']);
        $this->sections = []; // Clear dynamic sections
    }

    // --- CRUD Logic ---

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $this->employeeId = $id;
            $this->nik = $employee->nik;
            $this->name = $employee->name;
            $this->gender = $employee->gender;
            $this->phone = $employee->phone;
            $this->department_id = $employee->department_id;
            $this->position_id = $employee->position_id;

            // Load sections for the selected department
            $this->updatedDepartmentId($this->department_id);
            $this->section_id = $employee->section_id;

            $this->openModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Employee not found.', type: 'error');
        }
    }

    public function save()
    {
        $data = $this->validate();

        try {
            Employee::updateOrCreate(['id' => $this->employeeId], $data);

            $message = $this->employeeId ? 'Employee updated successfully.' : 'Employee created successfully.';
            $this->dispatch('show-toast', message: $message, type: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
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
}

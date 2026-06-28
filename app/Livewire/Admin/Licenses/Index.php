<?php

namespace App\Livewire\Admin\Licenses;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\License;
use App\Models\LicenseVersion;
use App\Models\Field;
use App\Models\Category;
use App\Models\DocumentType;
use App\Models\Department;
use App\Models\Section;
use App\Models\Employee;
use Carbon\Carbon;
use App\Models\ActionFrequencyUnit;
use App\Models\Rack;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    // Form fields
    public $licenseId;
    public $name_id;
    public $name_jp;
    public $field_id;
    public $category_id;
    public $document_type_id;
    public $occurrence_type;
    public $action_frequency_value;
    public $action_frequency_unit_id;
    public $government_issuer;
    public $start_date;
    public $end_date;
    public $department_id;
    public $section_id;
    public $owner_id;
    public $rack_id;
    public $status = 'Active';

    // File Upload Fields
    public $file;
    public $revision_notes;

    // Modal state
    public $isOpen = false;

    // Properti tabel
    public $search = '';
    public $perPage = 10;
    public $filter_department = '';
    public $filter_document_type = '';
    public $filter_category = '';
    public $filter_field = '';

    // Modal delete
    public $showDeleteModal = false;
    public $licenseIdToDelete = null;

    protected function rules()
    {
        return [
            'name_id'                => 'required|string|max:255',
            'name_jp'                => 'required|string|max:255',
            'field_id'               => 'required|exists:fields,id',
            'category_id'            => 'required|exists:categories,id',
            'document_type_id'       => 'required|exists:document_types,id',
            'occurrence_type'        => 'required|string',
            'action_frequency_value'   => 'nullable|integer',
            'action_frequency_unit_id' => 'nullable|exists:action_frequency_units,id',
            'government_issuer'        => 'nullable|string|max:255',
            'start_date'             => 'required|date',
            'end_date'               => 'required|date|after_or_equal:start_date',
            'department_id'          => 'required|exists:departments,id',
            'section_id'             => [
                'required',
                Rule::exists('sections', 'id')->where(function ($query) {
                    $query->where('department_id', $this->department_id);
                }),
            ],
            'owner_id'         => [
                'required',
                'exists:employees,id',
                // Validasi: Employee harus berasal dari department_id yang dipilih
                Rule::exists('employees', 'id')->where(function ($query) {
                    $query->where('department_id', $this->department_id);
                }),
            ],
            'rack_id'                  => 'nullable|exists:racks,id',
            'status'                 => 'required|string|max:50',
            // Aturan validasi file
            'file'                   => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'revision_notes'         => 'nullable|string|max:500',
        ];
    }

    public function render()
    {
        $licenses = License::with([
            'field',
            'category',
            'documentType',
            'department',
            'section',
            'owner'
        ])
            ->where(function ($query) {
                $query->where('name_id', 'like', '%' . $this->search . '%')
                    ->orWhere('name_jp', 'like', '%' . $this->search . '%');
            })
            ->when($this->filter_department, function ($query) {
                $query->where('department_id', $this->filter_department);
            })
            ->when($this->filter_document_type, function ($query) {
                $query->where('document_type_id', $this->filter_document_type);
            })
            ->when($this->filter_category, function ($query) {
                $query->where('category_id', $this->filter_category);
            })
            ->when($this->filter_field, function ($query) {
                $query->where('field_id', $this->filter_field);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.licenses.index', [
            'licenses'      => $licenses,
            'fields'        => Field::all(),
            'categories'    => Category::all(),
            'documentTypes' => DocumentType::all(),
            'departments'   => Department::all(),
            'sections'      => $this->department_id ? Section::where('department_id', $this->department_id)->get() : collect(),
            'employees'     => $this->department_id ? Employee::whereHas('user.roles', function ($query) {
                $query->whereIn('name', ['Senior Supervisor', 'Supervisor', 'Admin']);
            })->where('department_id', $this->department_id)->get() : collect(),
            'actionFrequencyUnits' => ActionFrequencyUnit::all(),
            'racks'         => Rack::all(),
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
        $this->reset([
            'licenseId',
            'name_id',
            'name_jp',
            'field_id',
            'category_id',
            'document_type_id',
            'occurrence_type',
            'action_frequency_value',
            'action_frequency_unit_id',
            'government_issuer',
            'start_date',
            'end_date',
            'department_id',
            'section_id',
            'owner_id',
            'file',
            'revision_notes',
            'rack_id'
        ]);
        $this->status = 'Active';
    }

    public function create()
    {
        Gate::authorize('create', License::class);

        $this->openModal();

        $user = auth()->user();
        if (!$user->hasRole('Admin')) {
            $this->department_id = $user->employee->department_id;
        }
    }

    public function edit($id)
    {
        try {
            $license = License::findOrFail($id);
            Gate::authorize('update', $license);

            $this->licenseId              = $id;
            $this->name_id                = $license->name_id;
            $this->name_jp                = $license->name_jp;
            $this->field_id               = $license->field_id;
            $this->category_id            = $license->category_id;
            $this->document_type_id       = $license->document_type_id;
            $this->occurrence_type        = $license->occurrence_type;
            $this->action_frequency_value = $license->action_frequency_value;
            $this->action_frequency_unit_id = $license->action_frequency_unit_id;
            $this->government_issuer      = $license->government_issuer;
            $this->start_date             = $license->start_date;
            $this->end_date               = $license->end_date;
            $this->department_id          = $license->department_id;
            $this->section_id             = $license->section_id;
            $this->owner_id               = $license->owner_id;
            $this->rack_id                = $license->rack_id;
            $this->status                 = $license->status;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'License not found.', type: 'error');
        }
    }

    public function save()
    {
        if ($this->licenseId) {
            $license = License::findOrFail($this->licenseId);
            Gate::authorize('update', $license);
        } else {
            Gate::authorize('create', License::class);
        }

        $data = $this->validate();

        // 1. Set default reminder ke 30 hari sebelum end_date
        $reminderDate = Carbon::parse($data['end_date'])->subDays(30);

        // 2. Jika user mengisi action frequency, kita gunakan untuk membuat reminder lebih akurat
        if (!empty($data['action_frequency_value']) && !empty($data['action_frequency_unit_id'])) {
            $unit = \App\Models\ActionFrequencyUnit::find($data['action_frequency_unit_id']);
            if ($unit) {
                $endDate = Carbon::parse($data['end_date']);
                $value = (int) $data['action_frequency_value'];
                $unitName = strtolower($unit->name);
                
                // Sesuaikan unit yang mungkin ada di database (bisa bahasa Indonesia atau Inggris)
                if (str_contains($unitName, 'year') || str_contains($unitName, 'tahun')) {
                    $reminderDate = $endDate->subYears($value);
                } elseif (str_contains($unitName, 'month') || str_contains($unitName, 'bulan')) {
                    $reminderDate = $endDate->subMonths($value);
                } elseif (str_contains($unitName, 'week') || str_contains($unitName, 'minggu')) {
                    $reminderDate = $endDate->subWeeks($value);
                } elseif (str_contains($unitName, 'day') || str_contains($unitName, 'hari')) {
                    $reminderDate = $endDate->subDays($value);
                }
            }
        }

        $data['reminder_date'] = $reminderDate;

        // Pisahkan data file
        $fileData = $this->file;
        $revisionNotes = $this->revision_notes;
        $isUpdate = (bool) $this->licenseId;
        unset($data['file'], $data['revision_notes']);

        try {
            DB::transaction(function () use ($data, $fileData, $revisionNotes, $isUpdate) {
                $license = License::updateOrCreate(['id' => $this->licenseId], $data);

                // Jika update, kita abaikan fileData (upload versi baru ada di halaman Show)
                if ($isUpdate) {
                    return;
                }

                if (!$fileData) {
                    return;
                }

                $latestVersion = LicenseVersion::where('license_id', $license->id)->max('version_number');
                $versionNumber = $latestVersion ? ($latestVersion + 1) : 1;

                $filePath = $fileData->store('licenses', 'supabase');

                LicenseVersion::create([
                    'license_id'     => $license->id,
                    'version_number' => $versionNumber,
                    'file_name'      => $fileData->getClientOriginalName(),
                    'file_path'      => $filePath,
                    'file_type'      => $fileData->getClientOriginalExtension(),
                    'file_size'      => $fileData->getSize(),
                    'revision_notes' => $revisionNotes ?: ($versionNumber === 1 ? 'First upload' : 'Uploaded new version'),
                    'uploader_id'    => auth()->id() ?? 1,
                ]);

                $license->update(['current_version' => $versionNumber]);
            });

            $message = $isUpdate ? 'License updated successfully.' : 'License created successfully.';
            $this->dispatch('show-toast', message: $message, type: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
        }
    }

    public function confirmDelete($id)
    {
        $license = License::findOrFail($id);
        Gate::authorize('delete', $license);

        $this->licenseIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->licenseIdToDelete) {
            try {
                $license = License::with('versions')->find($this->licenseIdToDelete);

                if ($license) {
                    Gate::authorize('delete', $license);
                    
                    foreach ($license->versions as $version) {
                        if ($version->file_path && Storage::disk('supabase')->exists($version->file_path)) {
                            Storage::disk('supabase')->delete($version->file_path);
                        }
                    }

                    $license->versions()->delete();
                    $license->delete();
                    $this->dispatch('show-toast', message: 'License deleted successfully.', type: 'success');
                }
            } catch (\Exception $e) {
                $this->dispatch('show-toast', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->licenseIdToDelete = null;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedFilterDepartment()
    {
        $this->resetPage();
    }

    public function updatedFilterDocumentType()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterField()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filter_department = '';
        $this->filter_document_type = '';
        $this->filter_category = '';
        $this->filter_field = '';
        $this->resetPage();
    }

    public function updatedDepartmentId($value)
    {
        if (!$value) {
            $this->section_id = null;
            return;
        }

        if (
            $this->section_id &&
            !Section::where('id', $this->section_id)->where('department_id', $value)->exists()
        ) {
            $this->section_id = null;
        }
    }
}

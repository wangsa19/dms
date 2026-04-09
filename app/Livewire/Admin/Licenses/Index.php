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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
    public $status = 'Active';

    // File Upload Fields
    public $file;
    public $revision_notes;

    // Modal state
    public $isOpen = false;

    // Properti tabel
    public $search = '';
    public $perPage = 10;

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
            'owner_id'               => 'required|exists:employees,id',
            'status'                 => 'required|string|max:50',
            // Aturan validasi file
            'file'                   => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'revision_notes'         => 'nullable|string|max:500',
        ];
    }

    public function render()
    {
        $licenses = License::with(['field', 'category', 'documentType', 'department', 'section', 'owner'])
            ->where(function ($query) {
                $query->where('name_id', 'like', '%' . $this->search . '%')
                    ->orWhere('name_jp', 'like', '%' . $this->search . '%')
                    ->orWhere('government_issuer', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.licenses.index', [
            'licenses'      => $licenses,
            'fields'        => Field::all(),
            'categories'    => Category::all(),
            'documentTypes' => DocumentType::all(),
            'departments'   => Department::all(),
            'sections'      => Section::when(
                $this->department_id,
                fn($query) => $query->where('department_id', $this->department_id),
                fn($query) => $query->whereRaw('1 = 0')
            )->get(),
            'employees'     => Employee::all(),
            'actionFrequencyUnits' => ActionFrequencyUnit::all(),
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
            'revision_notes'
        ]);
        $this->status = 'Active';
    }

    public function create()
    {
        abort_if(!auth()->user()->can('create licenses'), 403, 'Anda tidak memiliki akses untuk menambah lisensi.');

        $this->openModal();
    }

    public function edit($id)
    {
        if ($this->licenseId) {
            abort_if(!auth()->user()->can('edit licenses'), 403, 'Anda tidak memiliki akses untuk mengedit.');
        } else {
            abort_if(!auth()->user()->can('create licenses'), 403, 'Anda tidak memiliki akses untuk menambah data.');
        }

        try {
            $license = License::findOrFail($id);

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
            $this->status                 = $license->status;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'License not found.', type: 'error');
        }
    }

    public function save()
    {
        $data = $this->validate();

        $data['reminder_date'] = Carbon::parse($data['end_date'])->subDays(30);

        // Pisahkan data file
        $fileData = $this->file;
        $revisionNotes = $this->revision_notes;
        $isUpdate = (bool) $this->licenseId;
        unset($data['file'], $data['revision_notes']);

        try {
            DB::transaction(function () use ($data, $fileData, $revisionNotes) {
                $license = License::updateOrCreate(['id' => $this->licenseId], $data);

                if (!$fileData) {
                    return;
                }

                $latestVersion = LicenseVersion::where('license_id', $license->id)->max('version_number');
                $versionNumber = $latestVersion ? ($latestVersion + 1) : 1;

                $filePath = $fileData->store('licenses', 'public');

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
        abort_if(!auth()->user()->can('delete licenses'), 403, 'Anda tidak memiliki akses untuk menghapus lisensi.');

        $this->licenseIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        abort_if(!auth()->user()->can('delete licenses'), 403, 'Anda tidak memiliki akses untuk menghapus lisensi.');
        
        if ($this->licenseIdToDelete) {
            try {
                $license = License::with('versions')->find($this->licenseIdToDelete);

                if ($license) {
                    foreach ($license->versions as $version) {
                        if ($version->file_path && Storage::disk('public')->exists($version->file_path)) {
                            Storage::disk('public')->delete($version->file_path);
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

<?php

namespace App\Livewire\Admin\Documents;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // Tambahkan ini
use App\Models\Document;
use App\Models\DocumentVersion; // Tambahkan ini
use App\Models\DocumentType;
use App\Models\Category;
use App\Models\Field;
use App\Models\Department;
use App\Models\Section;
use App\Models\Employee;
use App\Models\Rack;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination, WithFileUploads; // Aktifkan upload file

    // Form fields
    public $documentId;
    public $name_id;
    public $name_jp;
    public $document_type_id;
    public $category_id;
    public $field_id;
    public $department_id;
    public $section_id;
    public $owner_id;
    public $status = 'Active';

    public $rack_id;
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
    public $documentIdToDelete = null;

    protected function rules()
    {
        return [
            'name_id'          => 'required|string|max:255',
            'name_jp'          => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'category_id'      => 'required|exists:categories,id',
            'field_id'         => 'required|exists:fields,id',
            'department_id'    => 'required|exists:departments,id',
            'section_id'       => [
                'required',
                Rule::exists('sections', 'id')->where(function ($query) {
                    $query->where('department_id', $this->department_id);
                }),
            ],
            'owner_id'         => [
                'required',
                Rule::exists('employees', 'id')->where(function ($query) {
                    $query->where('department_id', $this->department_id);
                }),
            ],
            'status'           => 'required|string|max:50',
            'rack_id'          => 'nullable|exists:racks,id',
            'file'             => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'revision_notes'   => 'nullable|string|max:500',
        ];
    }

    public function render()
    {
        $user = auth()->user();

        $documents = Document::with([
            'documentType',
            'category',
            'field',
            'department',
            'section',
            'owner'
        ])
            ->where(function ($query) {
                $query->where('name_id', 'like', '%' . $this->search . '%')
                    ->orWhere('name_jp', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.documents.index', [
            'documents'     => $documents,
            'documentTypes' => DocumentType::all(),
            'categories'    => Category::all(),
            'fields'        => Field::all(),
            'departments'   => Department::all(),
            'sections'      => $this->department_id ? Section::where('department_id', $this->department_id)->get() : collect(),
            'employees'     => $this->department_id ? Employee::whereHas('user.roles', function ($query) {
                $query->whereIn('name', ['Senior Supervisor', 'Supervisor', 'Admin']);
            })->where('department_id', $this->department_id)->get() : collect(),
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
            'documentId',
            'name_id',
            'name_jp',
            'document_type_id',
            'category_id',
            'field_id',
            'department_id',
            'section_id',
            'owner_id',
            'status',
            'file',
            'revision_notes',
            'rack_id'
        ]);
        $this->status = 'Active';
    }

    public function create()
    {
        $this->resetForm();
        $this->resetErrorBag();

        // Auto-fill departemen jika dia Supervisor/Senior Supervisor
        $user = auth()->user();
        if (!$user->hasRole('Admin')) {
            $this->department_id = $user->employee->department_id;
        }

        $this->isOpen = true;
    }

    public function edit($id)
    {
        try {
            $document = Document::findOrFail($id);
            Gate::authorize('update', $document);

            $this->documentId       = $id;
            $this->name_id          = $document->name_id;
            $this->name_jp          = $document->name_jp;
            $this->document_type_id = $document->document_type_id;
            $this->category_id      = $document->category_id;
            $this->field_id         = $document->field_id;
            $this->department_id    = $document->department_id;
            $this->section_id       = $document->section_id;
            $this->owner_id         = $document->owner_id;
            $this->status           = $document->status;
            $this->rack_id    = $document->rack_id;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Document not found.', type: 'error');
        }
    }

    public function save()
    {
        if ($this->documentId) {
            $document = Document::findOrFail($this->documentId);
            Gate::authorize('update', $document);
        } else {
            Gate::authorize('create', Document::class);
        }
        
        $data = $this->validate();

        // Pisahkan data file dari data dokumen utama
        $fileData = $this->file;
        $revisionNotes = $this->revision_notes;
        $isUpdate = (bool) $this->documentId;
        unset($data['file'], $data['revision_notes']);

        try {
            DB::transaction(function () use ($data, $fileData, $revisionNotes, $isUpdate) {
                $document = Document::updateOrCreate(['id' => $this->documentId], $data);

                // Jika update, kita abaikan fileData (upload versi baru ada di halaman Show)
                if ($isUpdate) {
                    return;
                }

                if (!$fileData) {
                    return;
                }

                $latestVersion = DocumentVersion::where('document_id', $document->id)->max('version_number');
                $versionNumber = $latestVersion ? ($latestVersion + 1) : 1;

                $filePath = $fileData->store('documents', 'public');

                DocumentVersion::create([
                    'document_id'    => $document->id,
                    'version_number' => $versionNumber,
                    'file_name'      => $fileData->getClientOriginalName(),
                    'file_path'      => $filePath,
                    'file_type'      => $fileData->getClientOriginalExtension(),
                    'file_size'      => $fileData->getSize(),
                    'revision_notes' => $revisionNotes ?: ($versionNumber === 1 ? 'First upload' : 'Uploaded new version'),
                    'uploader_id'    => auth()->id() ?? 1,
                ]);

                $document->update(['current_version' => $versionNumber]);
            });

            $message = $isUpdate ? 'Document updated successfully.' : 'Document created successfully.';
            $this->dispatch('show-toast', message: $message, type: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
        }
    }

    public function confirmDelete($id)
    {
        $this->documentIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->documentIdToDelete) {
            try {
                // Load dokumen beserta data versi/file-nya
                $document = Document::with('versions')->find($this->documentIdToDelete);

                if ($document) {
                    Gate::authorize('delete', $document);
                    
                    // 1. Hapus file fisiknya dari folder storage (biar storage nggak penuh)
                    foreach ($document->versions as $version) {
                        if ($version->file_path && Storage::disk('public')->exists($version->file_path)) {
                            Storage::disk('public')->delete($version->file_path);
                        }
                    }

                    // 2. Hapus data anaknya di database (tabel document_versions)
                    $document->versions()->delete();

                    // 3. Baru hapus data induknya (tabel documents)
                    $document->delete();

                    $this->dispatch('show-toast', message: 'Document deleted successfully.', type: 'success');
                }
            } catch (\Exception $e) {
                // Gua ubah pesan errornya biar nampilin error asli dari database kalau gagal lagi
                $this->dispatch('show-toast', message: 'Error: ' . $e->getMessage(), type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->documentIdToDelete = null;
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
        $this->section_id = null;
        $this->owner_id = null;

        if (!$value) return;

        // Pastikan section yang dipilih sebelumnya masih valid di dept baru (sudah ada di kode lo)
        if ($this->section_id && !Section::where('id', $this->section_id)->where('department_id', $value)->exists()) {
            $this->section_id = null;
        }
    }
}

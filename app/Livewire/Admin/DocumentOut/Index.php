<?php

namespace App\Livewire\Admin\DocumentOut;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DocumentOut;
use App\Models\Document;
use App\Models\Employee;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $documentOutId;
    public $document_id;
    public $borrower_id;
    public $checkout_time;
    public $return_time;
    public $status = 'Borrowed';

    // Filters
    public $filterStatus = '';
    public $filterDepartmentId = '';

    // Sorting
    public $sortField = 'document_outs.created_at';
    public $sortDirection = 'desc';

    // Modal state
    public $isOpen = false;
    public $showDeleteModal = false;
    public $documentOutIdToDelete = null;

    // Table properties
    public $perPage = 10;

    protected function rules()
    {
        return [
            'document_id'   => 'required|exists:documents,id',
            'borrower_id'   => 'required|exists:employees,id',
            'checkout_time' => 'required|date',
            'return_time'   => 'nullable|date|after_or_equal:checkout_time',
        ];
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('Admin');
        $deptId = $user->employee->department_id ?? null;

        // Query data document_outs dengan relasi document dan borrower (employee)
        $documentOuts = DocumentOut::select('document_outs.*')
            ->leftJoin('documents', 'document_outs.document_id', '=', 'documents.id')
            ->leftJoin('employees as borrowers', 'document_outs.borrower_id', '=', 'borrowers.id')
            ->leftJoin('departments', 'borrowers.department_id', '=', 'departments.id')
            ->with(['document.documentType', 'document.category', 'borrower.department'])
            ->when($this->filterStatus, function ($query) {
                $query->where('document_outs.status', $this->filterStatus);
            })
            ->when($this->filterDepartmentId, function ($query) {
                $query->where('documents.department_id', $this->filterDepartmentId);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Ambil ID dokumen yang sedang dipinjam (kecuali dokumen yang sedang diedit saat ini)
        $currentlyBorrowedDocumentIds = \App\Models\DocumentOut::whereIn('status', ['Borrowed', 'Late'])
            ->when($this->documentOutId, function ($q) {
                $q->where('id', '!=', $this->documentOutId);
            })
            ->pluck('document_id');

        return view('livewire.admin.document-out.index', [
            'documentOuts' => $documentOuts,
            'departments'  => \App\Models\Department::all(),
            'documents'    => Document::where('status', 'Active')
                                ->whereNotIn('id', $currentlyBorrowedDocumentIds)
                                ->when(!$isAdmin && $deptId, function ($q) use ($deptId) {
                                    $q->where('department_id', $deptId);
                                })->get(),
            'employees'    => Employee::with('user')->has('user')
                                ->when(!$isAdmin, function ($q) {
                                    $q->where('id', auth()->user()->employee_id);
                                })->get(),
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->isOpen = true;
    }

    public function create()
    {
        // Authorization check untuk Create
        abort_if(!auth()->user()->can('create document outs'), 403, 'Anda tidak memiliki akses untuk membuat data peminjaman.');

        $this->openModal();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetForm()
    {
        $this->reset([
            'documentOutId',
            'document_id',
            'borrower_id',
            'checkout_time',
            'return_time'
        ]);
        $this->status = 'Borrowed';
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('edit document outs'), 403, 'Anda tidak memiliki akses untuk mengedit data peminjaman.');

        $docOut = DocumentOut::findOrFail($id);

        // Hanya pembuat atau Admin yang bisa edit
        if ($docOut->created_by !== auth()->id() && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Anda hanya dapat mengedit riwayat peminjaman yang Anda buat sendiri.');
        }

        $this->documentOutId = $id;
        $this->document_id   = $docOut->document_id;
        $this->borrower_id   = $docOut->borrower_id;
        $this->checkout_time = $docOut->checkout_time ? \Carbon\Carbon::parse($docOut->checkout_time)->format('Y-m-d\TH:i') : null;
        $this->return_time   = $docOut->return_time ? \Carbon\Carbon::parse($docOut->return_time)->format('Y-m-d\TH:i') : null;
        $this->status        = $docOut->status;

        $this->resetErrorBag();
        $this->isOpen = true;
    }

    public function save()
    {
        if ($this->documentOutId) {
            abort_if(!auth()->user()->can('edit document outs'), 403, 'Anda tidak memiliki akses untuk mengedit.');
            
            $docOut = DocumentOut::findOrFail($this->documentOutId);
            if ($docOut->created_by !== auth()->id() && !auth()->user()->hasRole('Admin')) {
                abort(403, 'Anda hanya dapat mengedit riwayat peminjaman yang Anda buat sendiri.');
            }
        } else {
            abort_if(!auth()->user()->can('create document outs'), 403, 'Anda tidak memiliki akses untuk menambah data.');
        }

        $data = $this->validate();

        // Auto determine status
        $data['status'] = !empty($data['return_time']) ? 'Returned' : 'Borrowed';

        if (!$this->documentOutId) {
            $data['created_by'] = auth()->id();
        }

        try {
            DocumentOut::updateOrCreate(['id' => $this->documentOutId], $data);

            $message = $this->documentOutId ? 'Document Out updated successfully.' : 'Document Out created successfully.';
            $this->dispatch('show-toast', message: $message, type: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function confirmDelete($id)
    {
        abort_if(!auth()->user()->can('delete document outs'), 403, 'Anda tidak memiliki akses untuk menghapus data peminjaman.');

        $docOut = DocumentOut::findOrFail($id);
        if ($docOut->created_by !== auth()->id() && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Anda hanya dapat menghapus riwayat peminjaman yang Anda buat sendiri.');
        }

        $this->documentOutIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        abort_if(!auth()->user()->can('delete document outs'), 403, 'Anda tidak memiliki akses untuk menghapus data peminjaman.');
        
        if ($this->documentOutIdToDelete) {
            try {
                $docOut = DocumentOut::findOrFail($this->documentOutIdToDelete);
                if ($docOut->created_by !== auth()->id() && !auth()->user()->hasRole('Admin')) {
                    abort(403, 'Anda hanya dapat menghapus riwayat peminjaman yang Anda buat sendiri.');
                }
                
                $docOut->delete();
                $this->dispatch('show-toast', message: 'Document Out deleted successfully.', type: 'success');
            } catch (\Exception $e) {
                $this->dispatch('show-toast', message: 'Error: ' . $e->getMessage(), type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->documentOutIdToDelete = null;
    }

    public function resetFilters()
    {
        $this->reset(['filterStatus', 'filterDepartmentId']);
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterDepartmentId()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }
}

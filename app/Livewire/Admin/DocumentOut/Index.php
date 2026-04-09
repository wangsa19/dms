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

    // Modal state
    public $isOpen = false;
    public $showDeleteModal = false;
    public $documentOutIdToDelete = null;

    // Table properties
    public $search = '';
    public $perPage = 10;

    protected function rules()
    {
        return [
            'document_id'   => 'required|exists:documents,id',
            'borrower_id'   => 'required|exists:employees,id',
            'checkout_time' => 'required|date',
            'return_time'   => 'nullable|date|after_or_equal:checkout_time',
            'status'        => 'required|string|max:50',
        ];
    }

    public function render()
    {
        // Query data document_outs dengan relasi document dan borrower (employee)
        $documentOuts = DocumentOut::with(['document.documentType', 'document.category', 'borrower'])
            ->when($this->search, function ($query) {
                $query->whereHas('document', function ($q) {
                    $q->where('name_id', 'like', '%' . $this->search . '%')
                        ->orWhere('name_jp', 'like', '%' . $this->search . '%');
                })->orWhereHas('borrower', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%'); // Asumsi Employee punya kolom 'nik'
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.document-out.index', [
            'documentOuts' => $documentOuts,
            'documents'    => Document::where('status', 'Active')->get(),
            'employees'    => Employee::all(),
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

        $this->documentOutId = $id;
        $this->document_id   = $docOut->document_id;
        $this->borrower_id   = $docOut->borrower_id;
        $this->checkout_time = $docOut->checkout_time;
        $this->return_time   = $docOut->return_time;
        $this->status        = $docOut->status;

        $this->resetErrorBag();
        $this->isOpen = true;
    }

    public function save()
    {
        if ($this->documentOutId) {
            abort_if(!auth()->user()->can('edit document outs'), 403, 'Anda tidak memiliki akses untuk mengedit.');
        } else {
            abort_if(!auth()->user()->can('create document outs'), 403, 'Anda tidak memiliki akses untuk menambah data.');
        }

        $data = $this->validate();

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

        $this->documentOutIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        abort_if(!auth()->user()->can('delete document outs'), 403, 'Anda tidak memiliki akses untuk menghapus data peminjaman.');
        
        if ($this->documentOutIdToDelete) {
            try {
                DocumentOut::findOrFail($this->documentOutIdToDelete)->delete();
                $this->dispatch('show-toast', message: 'Document Out deleted successfully.', type: 'success');
            } catch (\Exception $e) {
                $this->dispatch('show-toast', message: 'Error: ' . $e->getMessage(), type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->documentOutIdToDelete = null;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }
}

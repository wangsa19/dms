<?php

namespace App\Livewire\Admin\Manage\Rack;

use Livewire\Component;
use App\Models\Rack;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $rackId, $name, $code, $column, $row;

    // Modal state
    public $isOpen = false;
    public $showDeleteModal = false;
    public $rackIdToDelete = null;

    // Properti tabel
    public $search = '';
    public $perPage = 10;

    /**
     * Aturan validasi yang disesuaikan dengan Field.
     */
    protected function rules()
    {
        return [
            'name'   => 'required|string|max:255',
            'code'   => [
                'required',
                'string',
                'max:255',
                Rule::unique('racks')->ignore($this->rackId),
            ],
            'column' => 'required|string|max:50',
            'row'    => 'required|string|max:50',
        ];
    }

    public function render()
    {
        $racks = Rack::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.rack.index', [
            'racks' => $racks,
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
        $this->reset(['rackId', 'name', 'code', 'column', 'row']);
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        try {
            $rack = Rack::findOrFail($id);
            $this->rackId = $id;
            $this->name   = $rack->name;
            $this->code   = $rack->code;
            $this->column = $rack->column;
            $this->row    = $rack->row;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Rack not found.', type: 'error');
        }
    }

    public function save()
    {
        abort_if(!auth()->user()->can($this->rackId ? 'edit racks' : 'create racks'), 403);

        $data = $this->validate();

        try {
            Rack::updateOrCreate(['id' => $this->rackId], $data);

            $message = $this->rackId ? 'Rack updated successfully.' : 'Rack created successfully.';
            $this->dispatch('show-toast', message: $message, type: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
        }
    }

    public function confirmDelete($id)
    {
        $this->rackIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        abort_if(!auth()->user()->can('delete racks'), 403);

        if ($this->rackIdToDelete) {
            try {
                $rack = Rack::find($this->rackIdToDelete);
                if ($rack) {
                    $rack->delete();
                    $this->dispatch('show-toast', message: 'Rack deleted successfully.', type: 'success');
                }
            } catch (\Exception $e) {
                $this->dispatch('show-toast', message: 'Could not delete rack. It might be in use.', type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->rackIdToDelete = null;
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
<?php

namespace App\Livewire\Admin\Manage\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Form fields
    public $categoryId;
    public $name;
    public $code;

    // Modal state
    public $isOpen = false;

    // Properti untuk tabel
    public $search = '';
    public $perPage = 10;

    // Properti untuk modal delete
    public $showDeleteModal = false;
    public $categoryIdToDelete = null;

    /**
     * Tentukan aturan validasi.
     */
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($this->categoryId),
            ],
            'code' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Render komponen.
     */
    public function render()
    {
        $categories = Category::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.manage.category.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Buka modal (untuk create atau edit).
     */
    public function openModal()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->isOpen = true;
    }

    /**
     * Tutup modal.
     */
    public function closeModal()
    {
        $this->isOpen = false;
    }

    /**
     * Reset form fields.
     */
    public function resetForm()
    {
        $this->reset(['categoryId', 'name', 'code']);
    }

    // --- Logika CRUD ---

    /**
     * Tampilkan modal untuk membuat kategori baru.
     */
    public function create()
    {
        $this->openModal();
    }

    /**
     * Tampilkan modal untuk mengedit kategori yang ada.
     */
    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            $this->categoryId = $id;
            $this->name = $category->name;
            $this->code = $category->code;

            $this->resetErrorBag();
            $this->isOpen = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Category not found.', type: 'error');
        }
    }

    /**
     * Simpan data (baik baru atau update).
     */
    public function save()
    {
        $data = $this->validate();

        // Convert empty string code to null
        $data['code'] = $data['code'] === "" ? null : $data['code'];

        try {
            Category::updateOrCreate(['id' => $this->categoryId], $data);

            $message = $this->categoryId ? 'Category updated successfully.' : 'Category created successfully.';
            $this->dispatch('show-toast', message: $message, type: 'success');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Tampilkan modal konfirmasi delete.
     */
    public function confirmDelete($id)
    {
        $this->categoryIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Hapus data kategori.
     */
    public function delete()
    {
        if ($this->categoryIdToDelete) {
            try {
                $category = Category::find($this->categoryIdToDelete);
                if ($category) {
                    $category->delete();
                    $this->dispatch('show-toast', message: 'Category deleted successfully.', type: 'success');
                } else {
                    $this->dispatch('show-toast', message: 'Category not found.', type: 'error');
                }
            } catch (\Exception $e) {
                // Tangani error jika data terkait dengan data lain
                $this->dispatch('show-toast', message: 'Could not delete category. It might be in use.', type: 'error');
            }
        }
        $this->showDeleteModal = false;
        $this->categoryIdToDelete = null;
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

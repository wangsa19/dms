<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Document;
use App\Models\DocumentOut;
use App\Models\Category;
use App\Models\DocumentType;
use App\Models\Department;
use App\Models\License;
use App\Models\User;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $role;

    // --- Properti Admin ---
    public $totalDocuments;
    public $totalDocsOut;
    public $todayDocsOut;
    public $todayDocsReturn;
    public $latestDocuments;
    public $categoryData;
    public $typeData;
    public $departmentData;
    public $outReturnLabels;
    public $outReturnSeries;

    // Tambahan sesuai skenario buku
    public $totalLicenses;
    public $totalUsers;

    // --- Properti Supervisor ---
    public $deptTotalDocuments;
    public $deptLatestDocuments;
    public $deptExpiringLicenses;

    // --- Properti Supervisor (Diperbarui Namanya) ---
    public $totalDocs;
    public $latestDocs;
    public $expiringLicenses;

    public function mount()
    {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            $this->role = 'Admin';
            $this->loadAdminData();
        } else {
            $this->role = 'Supervisor';
            $this->loadSupervisorData($user);
        }
    }

    private function loadAdminData()
    {
        // Data Tambahan untuk Admin
        $this->totalLicenses = License::count();
        $this->totalUsers = User::count();

        // Kode asli lu untuk Admin
        $this->totalDocuments = Document::count();
        $this->totalDocsOut = DocumentOut::where('status', 'Borrowed')->count();
        $this->todayDocsOut = DocumentOut::whereDate('created_at', Carbon::today())->count();
        $this->todayDocsReturn = DocumentOut::whereDate('return_time', Carbon::today())->count();
        $this->latestDocuments = Document::latest()->take(3)->get();

        $this->categoryData = Category::withCount('documents')->get();
        $this->typeData = DocumentType::withCount('documents')->get();
        $this->departmentData = Department::withCount('documents')->get();

        $months = collect(range(5, 0))->map(fn($i) => Carbon::now()->subMonths($i));
        $this->outReturnLabels = $months->map(fn($m) => $m->format('M'))->toArray();

        $outData = [];
        $returnData = [];

        foreach ($months as $month) {
            $outData[] = DocumentOut::where('status', 'Borrowed')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $returnData[] = DocumentOut::whereNotNull('return_time')
                ->whereMonth('return_time', $month->month)
                ->whereYear('return_time', $month->year)
                ->count();
        }

        $this->outReturnSeries = [
            ['name' => 'Docs Out', 'data' => $outData],
            ['name' => 'Docs Return', 'data' => $returnData]
        ];
    }

    private function loadSupervisorData($user)
    {
        $deptId = $user->employee->department_id ?? null;

        if ($deptId) {
            // Hitung Total Dokumen Departemen
            // $this->totalDocs = \App\Models\Document::where('department_id', $deptId)->count();
            $this->totalDocs = \App\Models\Document::count();

            // Hitung Total Lisensi Departemen (Tambahan baru untuk card terpisah)
            // $this->totalLicenses = \App\Models\License::where('department_id', $deptId)->count();
            $this->totalLicenses = \App\Models\License::count();

            // Ambil list dokumen terbaru
            // $this->latestDocs = \App\Models\Document::where('department_id', $deptId)
            //     ->latest()
            //     ->take(5)
            //     ->get();
            $this->latestDocs = \App\Models\Document::latest()
                ->take(5)
                ->get();

            // Reminder Lisensi yang akan expired
            // $this->expiringLicenses = \App\Models\License::where('department_id', $deptId)
            //     ->whereDate('end_date', '<=', \Carbon\Carbon::now()->addDays(30))
            //     ->take(5)
            //     ->get();
            $this->expiringLicenses = \App\Models\License::whereDate('end_date', '<=', \Carbon\Carbon::now()->addDays(30))
                ->take(5)
                ->get();
        } else {
            $this->totalDocs = 0;
            $this->totalLicenses = 0;
            $this->latestDocs = collect();
            $this->expiringLicenses = collect();
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}

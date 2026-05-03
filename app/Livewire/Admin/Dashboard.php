<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Document;
use App\Models\DocumentOut;
use App\Models\Category;
use App\Models\DocumentType;
use App\Models\Department;
use Carbon\Carbon;

class Dashboard extends Component
{
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

    public function mount()
    {
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

    public function render()
    {
        return view('livewire.admin.dashboard', [
            // Kamu bisa passing data chart di sini jika diperlukan
        ]);
    }
}

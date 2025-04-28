<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class PayrollFilesComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getSortedFilesProperty()
    {
        return collect(Storage::disk('local')->files('payrolls'))
            ->map(function ($file) {
                $fullPath = storage_path('app/' . $file);
                return [
                    'path' => $file,
                    'name' => basename($file),
                    'created_at' => File::exists($fullPath) ? File::lastModified($fullPath) : 0,
                ];
            })
            ->filter(function ($file) {
                return stripos($file['name'], $this->search) !== false;
            })
            ->sortByDesc('created_at')
            ->values();
    }
    
    public function render()
    {
                // Get all files from the payrolls directory
        $allFiles = File::allFiles(storage_path('app/payrolls'));

        // Map and sort them by created time (descending)
        $files = collect($allFiles)->map(function ($file) {
            return [
                'path' => str_replace(storage_path('app') . DIRECTORY_SEPARATOR, '', $file->getPathname()),
                'name' => $file->getFilename(),
                'created_at' => $file->getCTime(),
            ];
        })->filter(function ($file) {
            return stripos($file['name'], $this->search) !== false;
        })->sortByDesc('created_at')->values();

        // Paginate manually
        $currentPage = $this->page;
        $paginatedFiles = new LengthAwarePaginator(
            $files->forPage($currentPage, $this->perPage),
            $files->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.payroll-files-component', [
            'files' => $paginatedFiles,
        ]);
    }
}

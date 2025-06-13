<?php

namespace App\Http\Livewire;

use App\Models\Deduction;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadDeductionComponent extends Component
{

    use WithFileUploads;

    public $deductionTypes = [];
    public $uploadedFile;
    public $selectedDeductionType;

    public function updatedUploadedFile()
    {
        // Optional: you can add validation here if needed
        $this->validate([
            'uploadedFile' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);
    }

    public function uploadFile(){
        $storedFilePath = $this->uploadedFile->store('temp');
        return redirect()->route('edit-uploaded-deductions-landing-page', [
            'file' => basename($storedFilePath), // just the filename
            'selectedDeductionType' => $this->selectedDeductionType,
        ]);
    }

    public function render()
    {
        $this->deductionTypes = Deduction::orderBy('deduction_group')->pluck('deduction_group')->unique()->toArray();
        return view('livewire.upload-deduction-component');
    }
}

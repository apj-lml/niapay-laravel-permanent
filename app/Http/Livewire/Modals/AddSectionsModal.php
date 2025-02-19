<?php

namespace App\Http\Livewire\Modals;

use App\Models\AgencySection;
use Livewire\Component;

class AddSectionsModal extends Component
{
    protected $listeners = ['openEditSectionModal'];

    public $sectionCode,
            $sectionDescription,
            $isEditMode = false,
            $sectionId,
            $listOfOffices,
            $office,
            $newOffice = false;

    
    public function updated(){
        $this->validate([
            'sectionCode' => 'required',
            'sectionDescription' => 'required',
            'office' => 'required',

        ]);
    }

    public function openEditSectionModal($sectionId){
        // dd($sectionId);
        $agencySection = AgencySection::find($sectionId);
        $this->sectionCode = $agencySection->section_code;
        $this->sectionDescription = $agencySection->section_description;
        $this->office = $agencySection->office;
        $this->sectionId = $agencySection->id;

        $this->isEditMode = true;
    }

    public function addSectionForm()
    {
        if($this->isEditMode){
            $this->validate([
                'sectionCode' => 'required',
                'sectionDescription' => 'required',
                'office' => 'required',

            ]);
        }else{
            $this->validate([
                'sectionCode' => 'required|unique:agency_sections,section_code',
                'sectionDescription' => 'required',
                'office' => 'required',

            ]);
        }

        AgencySection::updateOrCreate(['id'=>$this->sectionId], [
            'section_code' => $this->sectionCode,
            'section_description' => $this->sectionDescription,
            'office' => $this->office,
        ]);

        if($this->isEditMode){
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully added to the database.']);
        }else{
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully saved changes.']);
        }

        $this->reset();
        $this->isEditMode = false;
        $this->emit('refreshListOfSections');
        $this->emit('refreshUnitsModal');

    }

    public function render()
    {
        $this->listOfOffices = AgencySection::select("office")
        ->groupBy('office')
        ->get();
        return view('livewire.modals.add-sections-modal');
    }
}

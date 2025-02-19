<?php

namespace App\Http\Livewire\Modals;

use Livewire\Component;
use App\Models\AgencySection;
use App\Models\AgencyUnit;

class AddUnitsModal extends Component
{
    protected $listeners = ['openEditUnitModal', 'refreshUnitsModal'=> '$refresh'];

    public $unitCode,
            $unitDescription,
            $agencySectionId = 1,
            $isEditMode = false,
            $unitId;

    protected $validationAttributes = [
        'agencySectionId' => 'section'
    ];

    public function updated(){
        $this->validate([
            'unitCode' => 'required',
            'unitDescription' => 'required',
            'agencySectionId' => 'required'
        ]);
    }
    
    public function openEditUnitModal($unitId){
        // dd($unitId);
        $agencyUnit = AgencyUnit::find($unitId);
        $this->unitCode = $agencyUnit->unit_code;
        $this->unitDescription = $agencyUnit->unit_description;
        $this->agencySectionId = $agencyUnit->agency_section_id;
        $this->unitId = $agencyUnit->id;

        $this->isEditMode = true;
    }

    public function addUnitForm()
    {
        if($this->isEditMode){
            $this->validate([
                'unitCode' => 'required',
                'unitDescription' => 'required',
                'agencySectionId' => 'required'
            ]);
        }else{
            $this->validate([
                'unitCode' => 'required|unique:agency_units,unit_code',
                'unitDescription' => 'required',
                'agencySectionId' => 'required'
            ]);
        }

        // dd($this->agencySectionId);
        AgencyUnit::updateOrCreate(['id'=>$this->unitId], [
            'unit_code' => $this->unitCode,
            'unit_description' => $this->unitDescription,
            'agency_section_id' => (int)$this->agencySectionId
        ]);

        if(!$this->isEditMode){
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully added to the database.']);
        }else{
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully saved changes.']);
        }

        $this->reset();
        $this->agencySectionId = 1;
        $this->isEditMode = false;
        $this->emit('refreshListOfUnits');

    }

    public function render()
    {

        $listOfSections = AgencySection::all();
        return view('livewire.modals.add-units-modal', ['listOfSections' => $listOfSections]);
    }
}
// 
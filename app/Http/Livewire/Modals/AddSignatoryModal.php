<?php

namespace App\Http\Livewire\Modals;

use App\Models\AgencySection;
use App\Models\Signatory;
use Livewire\Component;

class AddSignatoryModal extends Component
{

    protected $listeners = ['openEditSignatoryModal'];

    public $signatoryName,
            $position,
            $type = 'Box A [Preparer]',
            $signatoryId,
            $section = 1,
            $office = 'PIMO',
            $signatoryDocu = "wages",
            $isEditMode = false;


    protected $validationAttributes = [
                'agencySectionId' => 'section'
            ];
        
    public function updated(){
            $this->validate([
                'signatoryName' => 'required',
                'position' => 'required',
                'type' => 'required'
            ]);
            }
    public function openEditSignatoryModal($signatoryId){
        $signatory = Signatory::find($signatoryId);
        $this->signatoryName = $signatory->name;
        $this->position = $signatory->position;
        $this->section = $signatory->agency_section_id;
        $this->office = $signatory->office;
        $this->type = $signatory->type;
        $this->signatoryDocu = $signatory->signatoryDocu;

        $this->signatoryId = $signatory->id;

        $this->isEditMode = true;
    }
        

    public function addSignatoryForm()
    {
        
        $this->validate([
            'signatoryName' => 'required',
            'position' => 'required',
            'type' => 'required'
        ]);

        
        Signatory::updateOrCreate(['id'=>$this->signatoryId], [
            'name' => $this->signatoryName,
            'position' => $this->position,
            'agency_section_id' => $this->section,
            'office' => $this->office,
            'type' => $this->type,
            'docu' => $this->signatoryDocu
        ]);

        if(!$this->isEditMode){
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully added to the database.']);
            // $this->reset();

        }else{
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully saved changes.']);
        }
           
        $this->signatoryName = '';
        $this->signatoryDocu = 'wages';
        $this->position = '';
        $this->type = 'Box A [Preparer]';
        $this->isEditMode = false;
        $this->emit('refreshManageSignatories');
        // 
    }

    public function render()
    {
        $listOfSections = AgencySection::all();
        $listOfOffices = AgencySection::select('office')->distinct()->get();
        $listOfSignatories = Signatory::paginate(10);
        return view('livewire.modals.add-signatory-modal', ['listOfSignatory' => $listOfSignatories, 'listOfSections'=>$listOfSections, 'listOfOffices' => $listOfOffices]);
    }
}

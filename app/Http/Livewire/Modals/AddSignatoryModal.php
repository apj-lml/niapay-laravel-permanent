<?php

namespace App\Http\Livewire\Modals;

use App\Models\AgencySection;
use App\Models\Signatory;
use Livewire\Component;

class AddSignatoryModal extends Component
{

    protected $listeners = ['openEditSignatoryModal', 'resetFields'];

    public $signatoryName,
            $position,
            $type,
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

    public function openAddSignatoryModal()
        {
            $this->resetFields(); // clears all variables
        }

    public function resetFields()
        {
            $this->signatoryId = null;
            $this->signatoryName = '';
            $this->position = '';
            $this->section = 1;
            $this->office = 'PIMO';
            $this->type = '';
            $this->signatoryDocu = 'wages';
            $this->isEditMode = false;
        }

    public function getTypeOptionsProperty()
        {
            return match($this->signatoryDocu) {
                'wages' => [
                    'Box A [Preparer]',
                    'Box B [Section Chief Concerned]',
                    'Box C [Finance Unit Head]',
                    'Box D [Approver]',
                    'Box E [Certified]',
                ],
                'other_bonus' => [
                    'Box A [Preparer]',
                    'Box B [Certified]',
                    'Box C [Approved for Payment]',
                    'Box D [Certified]',
                ],
                default => [],
            };
        }

    public function openEditSignatoryModal($signatoryId){
        // $this->resetFields();

        $signatory = Signatory::find($signatoryId);
        $this->signatoryName = $signatory->name;
        $this->position = $signatory->position;
        $this->section = $signatory->agency_section_id;
        $this->office = $signatory->office;
        $this->type = $signatory->type;
        $this->signatoryDocu = $signatory->docu;

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

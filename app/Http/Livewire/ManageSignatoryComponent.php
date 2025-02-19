<?php

namespace App\Http\Livewire;

use App\Models\AgencySection;
use App\Models\Signatory;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ManageSignatoryComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteSignatory','refreshManageSignatories' => '$refresh'];
    public $searchVal = "";

    public $signatoryDocu = "";
    // protected $listeners = ['deleteSignatory', 'refreshManageSignatories'];

    // public function refreshManageSignatories()
    // {
    //     $this->dispatchBrowserEvent('refreshComponent', ['componentName' => '#signatoryTable']);
    // }



    public function signatoryDocuClick($signatoryDocuVal){
        $this->signatoryDocu = $signatoryDocuVal;
    }

    public function deleteSignatoryConfirmation($signatoryId){
        
        $this->dispatchBrowserEvent('deleteSignatoryConfirmation', ['signatoryId' => $signatoryId]);

    }

    public function deleteSignatory($signatoryId){
            Signatory::findOrFail($signatoryId)->delete();
            // $this->resetPage();
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully deleted Signatory!']);
    }

    public function showEditSignatoryModal($signatoryId)
    {
        $this->emit('openEditSignatoryModal', $signatoryId);
    }


    public function render()
    {
        // $listOfSignatories = Signatory::paginate(10);
        // $listOfSignatories = Signatory::all();
        $listOfSignatories = Signatory::where('name', 'like', '%'. $this->searchVal .'%')->where('docu', $this->signatoryDocu)->orderBy('office')->orderBy('name')->paginate(5);
        
        return view('livewire.manage-signatory-component', ['listOfSignatories'=>$listOfSignatories]);
    }
}

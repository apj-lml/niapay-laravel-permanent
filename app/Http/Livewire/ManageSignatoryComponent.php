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


    public function signatoryDocuClick($signatoryDocuVal)
    {
        $this->signatoryDocu = $signatoryDocuVal;
    }

    public function deleteSignatoryConfirmation($signatoryId)
    {
        $this->dispatchBrowserEvent('deleteSignatoryConfirmation', ['signatoryId' => $signatoryId]);
    }

    public function deleteSignatory($signatoryId){
        try {
            $signatory = Signatory::findOrFail($signatoryId);
            $signatory->delete();
    
            $this->dispatchBrowserEvent('fireToast', [
                'icon' => 'success',
                'title' => 'Successfully deleted Signatory!'
            ]);
        } catch (\Exception $e) {
            // $this->dispatchBrowserEvent('fireToast', [
            //     'icon' => 'error',
            //     'title' => 'Failed to delete signatory.'
            // ]);
        }
    }

    public function showEditSignatoryModal($signatoryId)
    {
        $this->emit('openEditSignatoryModal', $signatoryId);
    }

    public function getPageName()
    {
        return 'page_' . $this->signatoryDocu;
    }

    public function render()
    {

        $listOfSignatories = Signatory::where('docu', $this->signatoryDocu)
            ->orderBy('id', 'desc')
            ->paginate(5, ['*'], $this->getPageName()); // ✅ pass unique page name
        
        return view('livewire.manage-signatory-component', ['listOfSignatories'=>$listOfSignatories]);
    }
}

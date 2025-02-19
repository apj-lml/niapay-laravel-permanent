<?php

namespace App\Http\Livewire\SystemSettings;

use Livewire\Component;
use App\Models\AgencyUnit;
use App\Models\User;
use Livewire\WithPagination;

class ManageUnitsComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteUnit', 'refreshListOfUnits' => '$refresh'];

    public function deleteUnitConfirmation($unitId){
        $this->dispatchBrowserEvent('deleteUnitConfirmation', ['unitId' => $unitId]);
    }

    public function deleteUnit($unitId){
        $checkUserAgencyUnit = User::where('agency_unit_id', '=',$unitId)->first();
        if(!$checkUserAgencyUnit){
            AgencyUnit::findOrFail($unitId)->delete();
            $this->resetPage();
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully deleted Unit!']);

        }else{
            $this->dispatchBrowserEvent('invalidDeletion', ['constraints' => $checkUserAgencyUnit]);
        }
    }

    public function showEditUnitModal($unitId)
    {
        $this->emit('openEditUnitModal', $unitId);
    }

    public function render()
    {
        $listOfUnits = AgencyUnit::paginate(10, ['*'], 'agencyUnitPage');
        return view('livewire.system-settings.manage-units-component', ['listOfUnits' => $listOfUnits]);
    }
}

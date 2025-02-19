<?php

namespace App\Http\Livewire\SystemSettings;

use App\Models\Allowance;
use App\Models\AllowanceUser;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ManageAllowancesComponent extends Component
{
        use WithPagination;
        protected $paginationTheme = 'bootstrap';
        protected $listeners = ['deleteAllowance', 'refreshListOfAllowances' => '$refresh'];
        public $searchVal = "";

    
        public function deleteAllowanceConfirmation($allowanceId){
            $this->dispatchBrowserEvent('deleteAllowanceConfirmation', ['allowanceId' => $allowanceId]);
        }
    
        public function deleteAllowance($allowanceId){
            $checkUserAgencyAllowance = AllowanceUser::with('user')->where('allowance_id', '=', $allowanceId)->first();

            // dd($checkUserAgencyAllowance->toArray());
            if(is_null($checkUserAgencyAllowance)){
                Allowance::findOrFail($allowanceId)->delete();
                $this->resetPage();
                $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully deleted Allowance!']);
    
            }else{
                $this->dispatchBrowserEvent('invalidDeletion', ['constraints' => $checkUserAgencyAllowance]);
            }
        }
    
    public function showEditAllowanceModal($allowanceId)
        {
            $this->emit('openEditAllowanceModal', $allowanceId);
        }

    public function showAddAllowanceModal()
        {
            $this->emit('openAddAllowanceModal');
        }
    public function render()
    {
        // $listOfAllowances = Allowance::paginate(10);
        $listOfAllowances = Allowance::where('description', 'like', '%'. $this->searchVal .'%')->orderBy('allowance_group')->orderBy('description')->paginate(10, ['*'], 'allowancesPage');

        return view('livewire.system-settings.manage-allowances-component', ['listOfAllowances'=> $listOfAllowances]);
    }
}

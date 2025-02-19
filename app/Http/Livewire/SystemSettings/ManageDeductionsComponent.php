<?php

namespace App\Http\Livewire\SystemSettings;

use App\Models\Deduction;
use App\Models\DeductionUser;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ManageDeductionsComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteDeduction', 'refreshListOfDeductions' => '$refresh'];
    public $searchVal = "";

    public function deleteDeductionConfirmation($deductionId){
        $this->dispatchBrowserEvent('deleteDeductionConfirmation', ['deductionId' => $deductionId]);
    }

    public function deleteDeduction($deductionId){
        $checkUserAgencyDeduction = DeductionUser::with('user')->where('deduction_id', '=', $deductionId)->first();

        // dd($checkUserAgencyDeduction->toArray());
        if(is_null($checkUserAgencyDeduction)){
            Deduction::findOrFail($deductionId)->delete();
            $this->resetPage();
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully deleted Deduction!']);

        }else{
            $this->dispatchBrowserEvent('invalidDeletion', ['constraints' => $checkUserAgencyDeduction]);
        }
    }

    public function showEditDeductionModal($deductionId)
        {
            $this->emit('openEditDeductionModal', $deductionId);
        }

    public function showAddDeductionModal()
        {
            $this->emit('openAddDeductionModal');
        }


    public function render()
    {
        $listOfDeductions = Deduction::where('description', 'like', '%'. $this->searchVal .'%')->orderBy('deduction_group')->orderBy('description')->paginate(10, ['*'], 'deductionsPage');
        return view('livewire.system-settings.manage-deductions-component', ['listOfDeductions' => $listOfDeductions]);
    }
}

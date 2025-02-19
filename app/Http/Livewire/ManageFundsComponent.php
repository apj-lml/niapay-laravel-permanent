<?php

namespace App\Http\Livewire;

use App\Models\Fund;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ManageFundsComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteFund', 'refreshManageFundsComponent' => '$refresh'];

    public function deleteFundConfirmation($fundId){
        $this->dispatchBrowserEvent('deleteFundConfirmation', ['fundId' => $fundId]);
    }

    public function deleteFund($fundId){
        $checkUserFund = User::where('fund_id', '=', $fundId)->first();
        if(!$checkUserFund){
            Fund::findOrFail($fundId)->delete();
            $this->resetPage();
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully deleted fund!']);

        }else{
            $this->dispatchBrowserEvent('invalidDeletion', ['constraints' => $checkUserFund]);
        }
    }

    public function showEditFundModal($fundId)
    {
        $this->emit('openEditFundModal', $fundId);
    }

    public function showAddFundModal()
    {
        $this->emit('openAddFundModal');
    }

    public function render()
    {
        $listOfFunds = Fund::paginate(10);
        return view('livewire.manage-funds-component', ['listOfFunds' => $listOfFunds]);
    }
}

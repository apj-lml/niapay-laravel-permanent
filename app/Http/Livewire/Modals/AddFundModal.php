<?php

namespace App\Http\Livewire\Modals;

use App\Models\Fund;
use Livewire\Component;

class AddFundModal extends Component
{

    protected $listeners = ['openEditFundModal', 'openAddFundModal'];

    public $fundDescription,
            $fundId,
            $fundObligation,
            $fundUacs,
            $isEditMode = false;
        
    public function updated(){
            $this->validate([
                'fundDescription' => 'required',
                'fundObligation' => 'required',
                'fundUacs' => 'required',
            ]);
            }

    public function openEditFundModal($fundId){
        $fund = Fund::find($fundId);
        $this->fundDescription = $fund->fund_description;
        $this->fundObligation = $fund->fund_obligation_description;
        $this->fundUacs = $fund->fund_uacs_code;
        $this->fundId = $fund->id;

        $this->isEditMode = true;
    }

    public function openAddFundModal(){
        $this->reset();
    }
        

    public function addFundForm()
    {

        if($this->isEditMode){
            $this->validate([
                'fundDescription' => 'required',
                'fundObligation' => 'required',
                'fundUacs' => 'required',

            ]);
        }else{
            $this->validate([
                'fundDescription' => 'required|unique:funds,fund_description,fund_obligation_description,fund_uacs_code',
            ]);
        }
        
        Fund::updateOrCreate(['id'=>$this->fundId], [
            'fund_description' => $this->fundDescription,
            'fund_obligation_description' => $this->fundObligation,
            'fund_uacs_code' => $this->fundUacs
        ]);

        if(!$this->isEditMode){
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully added to the database.']);
            $this->reset();
        }else{
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully saved changes.']);
        }

        $this->isEditMode = false;
        $this->emit('refreshManageFundsComponent');
    }

    public function render()
    {
        return view('livewire.modals.add-fund-modal');
    }
}

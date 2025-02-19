<?php

namespace App\Http\Livewire\Modals;

use App\Models\Allowance;
use App\Models\AllowanceUser;
use Livewire\Component;

class AddAllowanceModal extends Component
{

    protected $listeners = ['openEditAllowanceModal', 'openAddAllowanceModal'];

    // public $allowanceDescription,
    //         $isEditMode = false,
    //         $allowanceId;

    public $allowanceDescription,
            $accountTitle,
            $allowanceType,
            $allowanceGroup,
            $isEditMode = false,
            $allowanceId,
            $uacsLfps,
            $uacsCob,
            $sortPosition,
            $allowanceFor = 'both',
            $status = "ACTIVE";


    protected $validationAttributes = [
        'agencySectionId' => 'section'
    ];

    public function updated(){
        $this->validate([
            'allowanceDescription' => 'required',
            'accountTitle' => 'required',
            'allowanceFor' => 'required',
            'uacsLfps' => 'required',
            'uacsCob' => 'required',
        ]);
    }

    public function hydrate()
    {
        $this->resetValidation();
    }

    public function openAddAllowanceModal()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function openEditAllowanceModal($allowanceId){
        $this->resetErrorBag();

        // dd($allowanceId);
        $allowance = Allowance::find($allowanceId);
        // $this->allowanceDescription = $agencyAllowance->description;
        // $this->allowanceId = $agencyAllowance->id;

        $this->allowanceDescription = $allowance->description;
        $this->accountTitle = $allowance->allowance_title;
        $this->allowanceGroup = $allowance->allowance_group;
        $this->allowanceFor = $allowance->allowance_for;
        $this->allowanceId = $allowance->id;
        $this->status = $allowance->status;

        $this->uacsCob = $allowance->uacs_code_cob;
        $this->uacsLfps = $allowance->uacs_code_lfps;

        $this->isEditMode = true;
    }

    public function addAllowanceForm()
    {
        if($this->isEditMode){
            $this->validate([
                'allowanceDescription' => 'required|unique:allowances,description,' . $this->allowanceId,
                'accountTitle' => 'required',
                'uacsLfps' => 'required',
                'uacsCob' => 'required',
                'allowanceFor' => 'required',
            ]);
        }else{
            $this->validate([
                'allowanceDescription' => 'required|unique:allowances,description',
                'accountTitle' => 'required',
                'uacsLfps' => 'required',
                'uacsCob' => 'required',
                'allowanceFor' => 'required',
            ]);
        }


        if(is_null($this->allowanceGroup)){
            $this->allowanceGroup = $this->allowanceDescription;
        }

        if(empty($this->sortPosition)){
            $lastSortPosition = Allowance::where('allowance_group', '=', $this->allowanceGroup)->orderBy('sort_position', 'desc')->first();
            if(is_null($lastSortPosition)){
                $this->sortPosition = 0;
            }else{
                $this->sortPosition = $lastSortPosition->sort_position + 1;
            }
        }
        // dd($this->agencySectionId);
        Allowance::updateOrCreate(['id'=>$this->allowanceId], [
            'description' => strtoupper($this->allowanceDescription),
            'account_title' => strtoupper($this->accountTitle),
            'allowance_type' => strtoupper(str_replace(' ', '_', $this->allowanceDescription)),
            'allowance_group' => strtoupper(str_replace(' ', '_', $this->allowanceGroup)),
            'allowance_for' => $this->allowanceFor,
            'status' => $this->status,
            'uacs_code_lfps' => $this->uacsLfps,
            'uacs_code_cob' => $this->uacsCob,
            'sort_position' => $this->sortPosition
        ]);

        if(!$this->isEditMode){
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully added to the database.']);
        }else{
            // $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully saved changes.']);
            $selectUserAllowances = AllowanceUser::where('allowance_id', $this->allowanceId)->get();
            foreach($selectUserAllowances as $userAllowance){
                $data = AllowanceUser::findOrFail($userAllowance->id);        
                if($this->status == 'ACTIVE'){
                    $is_active = 1;
                }else{
                    $is_active = 0;
                }

                    $data->update([
                        'active_status' => $is_active,
                    ]);
                
            }
            // dd($selectUserAllowances->get());
        }

        $this->reset();
        $this->isEditMode = false;
        $this->emit('refreshListOfAllowances');
        // $this->dispatchBrowserEvent('closeModal', ['id' => 'addAllowanceModal']);
    }

    public function render()
    {
        return view('livewire.modals.add-allowance-modal');
    }
}

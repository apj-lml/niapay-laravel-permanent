<?php

namespace App\Http\Livewire\Modals;

use App\Models\AgencyUnit;
use App\Models\Fund;
use Livewire\Component;
use App\Models\User;
use App\Models\DeductionUser;
use App\Models\AllowanceUser;

class EmployeeProfileModal extends Component
{
    public $employeeProfile,
            $isStepDisabled = '',
            $profileIsLoaded = 'd-block',
            $userDailyRate,
            $userId,
            $userSgJg,
            $activeStatus,
            $isLessFifteen;
            // $employee_id,
            // $name,
            // $last_name,
            // $first_name,
            // $middle_name,
            // $name_extn,
            // $agency_unit_id,
            // $position,
            // $employment_status,
            // $sg_jg,
            // $step,
            // $daily_rate,
            // $monthly_rate,
            // $fund_id;

    protected $rules = [
        'employeeProfile.employee_id' => 'required',
        'employeeProfile.is_active' => 'required',
        'employeeProfile.last_name' => 'required',
        'employeeProfile.first_name' => 'required',
        'employeeProfile.middle_name' => '',
        'employeeProfile.name_extn' => '',
        // 'employeeProfile.section_id' => 'required',
        'employeeProfile.agency_unit_id' => 'required',
        'employeeProfile.position' => 'required',
        'employeeProfile.employment_status' => 'required',
        'employeeProfile.sg_jg' => 'required',
        'employeeProfile.step' => 'required',
        'employeeProfile.daily_rate' => 'nullable',
        'employeeProfile.monthly_rate' => 'nullable',
        'employeeProfile.fund_id' => 'required',
        'employeeProfile.tin' => 'required',
        'employeeProfile.phic_no' => 'required',
        'employeeProfile.hdmf' => 'required',
        'employeeProfile.gsis' => 'required',
        'employeeProfile.is_less_fifteen' => 'required'
    ];




    protected $listeners = ['openEmployeeProfileModal'];

    public function hydrateEmployeeProfile()
    {
        $this->profileIsLoaded = 'd-none';
    }

    public function dehydrateEmployeeProfile()
    {
        $this->profileIsLoaded = 'd-block';
    }

    public function openEmployeeProfileModal($userId)
    {
        // $this->reset('employeeProfile');
        // $this->employeeProfile = User::findOrFail($userId);

        $this->userId = $userId;
        $this->employeeProfile = User::findOrFail($userId);

        $this->userDailyRate = $this->employeeProfile->daily_rate;
        $this->userSgJg = $this->employeeProfile->sg_jg;
        $this->activeStatus = $this->employeeProfile->is_active;
        $this->isLessFifteen = $this->employeeProfile->is_less_fifteen;

        // $this->emit('openEmployeeAllowancesTab', $userId);

    }

    function updatedEmployeeProfileDailyRate($value){
        $this->userDailyRate = (float) str_replace(",","", $value);
        // dd('IM HERE: ' . $value);
        // dd('IM HERE: ' . $this->userDailyRate);

    }

    public function changeActiveStatus()
    {
        if($this->activeStatus == 1){
            $this->employeeProfile->is_active = 0;
        }else{
            $this->employeeProfile->is_active = 1;
        }

    }

    public function changeIsLessFifteen()
    {

        if($this->isLessFifteen == 1){
            $this->employeeProfile->is_less_fifteen = 0;
        }else{
            $this->employeeProfile->is_less_fifteen = 1;
        }

        // dd($this->employeeProfile->is_less_fifteen);


    }


    public function AutoAddDeduction()
    {

        // DEDUCTIONS

        $checkDupePagIbig = DeductionUser::
                        where('deduction_id', '=', 1)
                        ->where('user_id', '=', $this->userId)
                        ->first();

        $checkDupeWht = DeductionUser::
                            where('deduction_id', '=', 8)
                            ->where('user_id', '=', $this->userId)
                            ->first();

        $checkDupePhic = DeductionUser::
                            where('deduction_id', '=', 5)
                            ->where('user_id', '=', $this->userId)
                            ->first();

        $checkDupeGsis = DeductionUser::
                            where('deduction_id', '=', 9)
                            ->where('user_id', '=', $this->userId)
                            ->first();

        $gsisAmount = (($this->userDailyRate * 22) * .09);
        if(!$checkDupeGsis){
            DeductionUser::create([
                'user_id' => $this->userId,
                'deduction_id'=> 9,
                'amount'=> $gsisAmount,
                'frequency'=> 1,
                'active_status'=> 1
            ]);
        }else{
            $checkDupeGsis->update([
                'user_id' => $this->userId,
                'deduction_id' => 9,
                'amount' => $gsisAmount,
                'frequency'=> 1
            ]);
        }
        

        $phicAmount = (($this->userDailyRate * 22) * .025);
        if(!$checkDupePhic){
            DeductionUser::create([
                'user_id' => $this->userId,
                'deduction_id'=> 5,
                'amount'=> $phicAmount,
                'frequency'=> 1,
                'active_status'=> 1
            ]);
        }else{
            $checkDupePhic->update([
                'user_id' => $this->userId,
                'deduction_id' => 5,
                'amount' => $phicAmount,
                'frequency'=> 1
            ]);
        }
        

        $pagIbigAmount = (($this->userDailyRate * 22) * .02);

        if(!$checkDupePagIbig){
            DeductionUser::create([
                'user_id' => $this->userId,
                'deduction_id'=> 1,
                'amount'=> $pagIbigAmount,
                'frequency'=> 1,
                'active_status'=> 1
            ]);
        }else{
            $checkDupePagIbig->update([
                'user_id' => $this->userId,
                'deduction_id' => 1,
                'amount' => $pagIbigAmount,
                'frequency' => 1,
            ]);
        }
        
    }



    public function dailyOrMonthly($employmentStatus = "CASUAL")
    {
        if($employmentStatus == 'CASUAL'){
            $this->isStepDisabled = '';
        }else{
            $this->isStepDisabled = 'disabled';
        }
    }

    public function closeModal()
    {
        $this->reset();
        $this->emit('closeEmployeeDeductionsTab');
        $this->emit('closeEmployeeAllowancesTab');
        $this->dispatchBrowserEvent('closeDeductionsTab');
    }


    public function saveProfile()
    {
        $this->validate();
        $this->AutoAddDeduction();

        $this->employeeProfile->save();

        $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully updated profile.']);
        $this->emit('refreshProcessPayrollJobOrderComponent');
    }


    public function clickEmployeeDeductionsTab($userId)
    {
        $this->emit('openEmployeeDeductionsTab', $userId);
    }

    public function clickEmployeeAllowancesTab($userId)
    {
        $this->emit('openEmployeeAllowancesTab', $userId);
    }

    public function render()
    {
        $listOfUnits = AgencyUnit::all()->sortBy('agency_section_id');
        $listOfFunds = Fund::all()->sortBy('fund_description');
        
        return view('livewire.modals.employee-profile-modal', ['listOfUnits'=>$listOfUnits, 'listOfFunds'=>$listOfFunds]);
    }
}

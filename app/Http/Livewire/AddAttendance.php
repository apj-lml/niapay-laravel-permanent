<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\User;
use App\Models\Attendance;
use App\Models\DeductionUser;
use App\Models\AllowanceUser;
use App\Http\Modals\AddIndividualAttendanceModal;

use Livewire;

class AddAttendance extends Component
{
    public $startDate,
    $endDate,
    $isLessFifteen = 'full_month',
    // $payrollDateFrom,
    // $payrollDateTo,
    $firstHalf = '0.00',
    $secondHalf = '0.00',
    $daysRendered = '0.00',
    $employmentStatus = "CASUAL",
    // $listOfEmployeesToConfigure,
    $searchVal = "",
    $counter = 0,
    $configuredData = [],
    $checkAttendanceDupeisEmpty;

    //this is from Print Payroll
    protected $listeners = ['fieldUpdated' => 'handleFieldUpdate'];

    public function handleFieldUpdate($field, $value)
    {
        // Handle the update
        if ($field === 'isLessFifteen') {
            $this->isLessFifteen = $value;
        }else if($field === 'payrollDateFrom'){
            $this->startDate = $value;
        }else if($field === 'payrollDateTo'){
            $this->endDate = $value;
        }
    }
        
    public function mount($isLessFifteen, $startDate, $endDate)
    {
        // Assign the passed variables to the component properties
        // $this->isLessFifteen = $isLessFifteen;
        // $this->startDate = $startDate;
        // $this->endDate = $endDate;
    }

    public function updatedIsLessFifteen()
    {
        $this->firstHalf = '0.00';
        $this->secondHalf = '0.00';
        $this->daysRendered = '0.00';
    }

    protected $validationAttributes = [
        'configuredData.*.configuredDaysRendered' => 'number of days'
    ];


    public function AutoAddDeduction($userId, $userDailyRate)
    {
        $isLessFifteen = $this->isLessFifteen;

        // ALLOWANCES
        $checkDupePera = AllowanceUser::
                        where('allowance_id', '=', 4)
                        ->where('user_id', '=', $userId)
                        ->first();

        $checkAttendance = false;
        
        if($isLessFifteen == 'full_month'){;
            if($checkDupePera){
                $checkAttendance = Attendance::where('user_id', $userId)
                    // ->where('start_date', $this->startDate)
                    // ->where('end_date', $this->endDate)
                    ->where('first_half', '>', 0)
                    ->where('second_half', '>', 0)
                    ->get();
            }

            $pera = ($this->daysRendered * 90.91);

            if($pera > 2000){
                $pera = 2000;
            }
        }else if($isLessFifteen == 'less_fifteen_first_half'){

            if($checkDupePera){
                $checkAttendance = Attendance::where('user_id', $userId)
                    ->where('first_half', '>', 0)
                    ->where('second_half', '=', 0)
                    ->get();
            }

            // dd($checkDupePera);

            $pera = ($this->firstHalf * 90.91);

            if($pera > 1000){
                $pera = 1000;
            }
        }else{

            if($checkDupePera){
                $checkAttendance = Attendance::where('user_id', $userId)
                    ->where('first_half', '=', 0)
                    ->where('second_half', '>', 0)
                    ->get();
            }


            $pera = ($this->secondHalf * 90.91);

            if($pera > 1000){
                $pera = 1000;
            }
        }
        

        if(!$checkDupePera){
            AllowanceUser::create([
                'user_id' => $userId,
                'allowance_id'=> 4,
                'amount'=> $pera,
                'frequency'=> 1,
                'active_status'=> 1
            ]);
        }else{
            // if($checkAttendance->isEmpty()){
                $checkDupePera->update([
                    'user_id' => $userId,
                    'allowance_id' => 4,
                    'amount' => $pera,
                    'frequency' => 1,
                ]);
                // dd($pera);
            // }
                
        }

        // DEDUCTIONS
        $checkDupePagIbig = DeductionUser::
                        where('deduction_id', '=', 1)
                        ->where('user_id', '=', $userId)
                        ->first();

        $checkDupeWht = DeductionUser::
                            where('deduction_id', '=', 8)
                            ->where('user_id', '=', $userId)
                            ->first();

        $checkDupePhic = DeductionUser::
                            where('deduction_id', '=', 5)
                            ->where('user_id', '=', $userId)
                            ->first();

        $checkDupeGsis = DeductionUser::
                            where('deduction_id', '=', 9)
                            ->where('user_id', '=', $userId)
                            ->first();

        $gsisAmount = (($userDailyRate * 22) * .09);
        if(!$checkDupeGsis){
            DeductionUser::create([
                'user_id' => $userId,
                'deduction_id'=> 9,
                'amount'=> $gsisAmount,
                'frequency'=> 1,
                'active_status'=> 1
            ]);
        }
        

        $phicAmount = bcdiv((($userDailyRate * 22) * .025), 1, 2);
        if(!$checkDupePhic){
            DeductionUser::create([
                'user_id' => $userId,
                'deduction_id'=> 5,
                'amount'=> $phicAmount,
                'frequency'=> 1,
                'active_status'=> 1
            ]);
        }else{
            // $checkDupePhic->update([
            //     'user_id' => $userId,
            //     'deduction_id' => 5,
            //     'amount' => $phicAmount,
            //     'frequency'=> 1
            // ]);
        }
        

        $pagIbigAmount = (($userDailyRate * 22) * .02);

        if(!$checkDupePagIbig){
            DeductionUser::create([
                'user_id' => $userId,
                'deduction_id'=> 1,
                'amount'=> $pagIbigAmount,
                'frequency'=> 1,
                'active_status'=> 1
            ]);
        }else{
            // $checkDupePagIbig->update([
            //     'user_id' => $userId,
            //     'deduction_id' => 1,
            //     'amount' => $pagIbigAmount,
            //     'frequency' => 1,
            // ]);
        }
        
    }

    public function addAttendance(){

        $this->daysRendered = (float)$this->firstHalf + (float)$this->secondHalf;
        $isLessFifteen = $this->isLessFifteen;


        $this->validate([
            'startDate' => 'required',
            'endDate' => 'required',
            // 'firstHalf' => 'gt:0',
            // 'secondHalf' => 'gt:0',
            'employmentStatus' => 'required',
        ]);

        
        // dd($this->daysRendered);
        if($isLessFifteen == 'full_month'){
            $employeeByEmploymentStatus = User::where('employment_status', '=', 'CASUAL')
            ->where('is_active', '=', 1)
            ->where('include_to_payroll', '=', 1)
            ->get();
        }else{
            $employeeByEmploymentStatus = User::where('employment_status', '=', 'CASUAL')
            ->where('is_active', '=', 1)
            ->where('include_to_payroll', '=', 1)
            ->where('is_less_fifteen', '=', 1)
            ->get();
        }

        foreach($employeeByEmploymentStatus as $employee){
            $checkDupes = Attendance::where('user_id', $employee->id)
            ->where('start_date', $this->startDate)
            ->where('end_date', $this->endDate)
            ->get();

            $this->checkAttendanceDupeisEmpty = $checkDupes->isEmpty();
            $this->AutoAddDeduction($employee->id, $employee->daily_rate);
            
            if($checkDupes->isEmpty()){
                if((float)$this->daysRendered > 0){
                    

                Attendance::create([
                    'user_id' => $employee->id,
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                    'days_rendered' => $this->daysRendered,
                    'first_half' => $this->firstHalf,
                    'second_half' => $this->secondHalf
                ]);
                if( !next( $employeeByEmploymentStatus ) ) {
                    $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Attendances are successfully added to the database.']);
                }
            }
                   

            }else{
                if((float)$this->daysRendered > 0){
                    // dd($checkDupes);
                    $selectAttendanceUser = Attendance::findOrFail($checkDupes[0]->id);

                    $selectAttendanceUser->days_rendered = $this->firstHalf + $this->secondHalf;
                    $selectAttendanceUser->first_half = $this->firstHalf;
                    $selectAttendanceUser->second_half = $this->secondHalf;
                    $selectAttendanceUser->save();

                        if( !next( $employeeByEmploymentStatus ) ) {
                            $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Attendance record updated!']);
                        }
    

                }else{
                    $this->deleteAttendance();
                    if( !next( $employeeByEmploymentStatus ) ) {
                        $this->dispatchBrowserEvent('fireToast', ['icon' => 'error', 'title' => 'Attendance record removed!']);
                    }
                }
            }
        }


    }

    public function deleteAttendance(){
        $checkDupes = Attendance::where('start_date', $this->startDate)
        ->where('end_date', $this->endDate)
        ->get();

        if($checkDupes->isNotEmpty()){
            error_log($checkDupes);
            foreach($checkDupes as $dupe){
                $selectAttendance = Attendance::findOrFail($dupe->id)->delete();
                $this->daysRendered = '0.000';
                $this->firstHalfDaysRendered = '0.000';
                $this->secondHalfDaysRendered = '0.000';
            }

            
            // $this->dispatchBrowserEvent('fireToast', ['error' => 'danger', 'title' => 'Attendance record removed!']);
        }
        $this->emit('refreshProcessPayrollJobOrderComponent');
    }

    public function updatedSearchVal()
    {
        $this->configureAttendance();
    }

    public function updatedDaysRendered(){
        $this->counter = 0;
    }

    public function updatedEmploymentStatus(){
        $this->counter = 0;
    }

    public function configureAttendance()
    {
        $this->daysRendered = $this->firstHalf + $this->secondHalf;

        if($this->counter == 0){
            $this->configuredData=[];
            $listOfEmployeesToConfigure = User::where("employment_status", "=", $this->employmentStatus)
            ->where('is_active', '=', 1)
            ->where('include_to_payroll', '=', 1)
            ->get();
        
            foreach($listOfEmployeesToConfigure as $employee){
                $newArr = array(
                    'id'=>$employee->id,
                    'name'=>$employee->name,
                    'configuredDaysRendered'=>$this->daysRendered,
                    'configuredFirstHalf'=>$this->firstHalf,
                    'configuredSecondHalf'=>$this->secondHalf,
                );
                array_push($this->configuredData, $newArr);
            }
            
            $this->counter++;
        }
    }

    public function addConfiguredAttendance()
    {
        $this->validate([
            'configuredData.*.configuredDaysRendered' => 'required|gt:0',
            'startDate' => 'required',
            'endDate' => 'required',
            'daysRendered' => 'required:gt:0',
            'firstHalf' => 'required|gt:0',
            'secondHalf' => 'required|gt:0',
            'employmentStatus' => 'required',
        ]);

        $employeeByEmploymentStatus = User::where('employment_status', '=', $this->employmentStatus)
        ->where('is_active', '=', 1)
        ->where('include_to_payroll', '=', 1)
        ->get();

        for($i = 0; $i<count($this->configuredData);$i++){
            $checkDupes = Attendance::where('user_id', $this->configuredData[$i]['id'])
            ->where('start_date', $this->startDate)
            ->where('end_date', $this->endDate)
            ->get();
            
            if($checkDupes->isEmpty()){
                Attendance::create([
                    'user_id' => $this->configuredData[$i]['id'],
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                    'days_rendered' => $this->configuredData[$i]['configuredDaysRendered'],
                    'first_half' => $this->configuredData[$i]['configuredFirstHalf'],
                    'second_half' => $this->configuredData[$i]['configuredSecondHalf'],
                ]);

            if( !next( $employeeByEmploymentStatus ) ) {
                $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Attendances are successfully added to the database.']);
            }

            }else{
                $this->dispatchBrowserEvent('fireToast', ['icon' => 'warning', 'title' => 'These records already exist.']);
            }
        }
    }

    public function render()
    {
        return view('livewire.add-attendance');
    }
    
}

<?php

namespace App\Http\Livewire\Modals;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\User;
use App\Models\DeductionUser;
use App\Models\AllowanceUser;


class AddIndividualAttendanceModal extends Component
{
    public $daysRendered = 0,
            $firstHalfDaysRendered = 0,
            $secondHalfDaysRendered = 0,
            $startDate,
            $endDate,
            $userId,
            $attendanceId = null,
            $userDailyRate,
            $isLessFifteen,
            $userSgJg;

    public function AutoAddDeduction()
    {
        $isLessFifteen = $this->isLessFifteen;

        //ALLOWANCES
        $checkDupePera = AllowanceUser::
                        where('allowance_id', '=', 4)
                        ->where('user_id', '=', $this->userId)
                        ->first();

        if($isLessFifteen != 'full_month'){

            if($checkDupePera){
                $checkAttendance = Attendance::where('user_id', $this->userId)
                    ->where('first_half', '>', 0)
                    ->where('second_half', '=', 0)
                    ->get();
            }

            $pera = (($this->firstHalfDaysRendered + $this->secondHalfDaysRendered) * 90.91);

            if($pera > 1000){
                $pera = 1000;
            }

            if(!$checkDupePera){
                AllowanceUser::create([
                    'user_id' => $this->userId,
                    'allowance_id'=> 4,
                    'amount'=> $pera,
                    'frequency'=> 1,
                    'active_status'=> 1
                ]);
            }else{
                $checkDupePera->update([
                    'user_id' => $this->userId,
                    'allowance_id' => 4,
                    'amount' => $pera,
                    'frequency' => 1,
                ]);
            }

        }
        

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
        }


        $phicAmount = bcdiv((($this->userDailyRate * 22) * .025), 1, 2);
        if(!$checkDupePhic){
            DeductionUser::create([
                'user_id' => $this->userId,
                'deduction_id'=> 5,
                'amount'=> $phicAmount,
                'frequency'=> 1,
                'active_status'=> 1
            ]);
        }else{
            // $checkDupePhic->update([
            //     'user_id' => $this->userId,
            //     'deduction_id' => 5,
            //     'amount' => $phicAmount,
            //     'frequency'=> 1
            // ]);
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
            // $checkDupePagIbig->update([
            //     'user_id' => $this->userId,
            //     'deduction_id' => 1,
            //     'amount' => $pagIbigAmount,
            //     'frequency' => 1,
            // ]);
        }


        // if(!$checkDupeWht && $this->userSgJg >= 13){
        // if($this->userSgJg >= 8){

        //     $grossPay = (($this->userDailyRate * 22) * 12) - ($gsisAmount * 12) - ($pagIbigAmount * 12) - ($phicAmount * 12) - (100 * 12);
        //     $whtAmount = 0.00;

        //     dd($grossPay);
        //     if(($grossPay * 12) > 250000 && ($grossPay * 12) < 400000){
        //         $whtAmount = ((($grossPay * 12) - 250000) * 0.15) / 12;
        //     }

        //     if ($checkDupeWht !== null) {
        //         // If a record exists, update it
        //         $checkDupeWht->update([
        //             'user_id' => $this->userId,
        //             'deduction_id' => 8,
        //             'amount' => $whtAmount,
        //             'frequency' => 1,
        //         ]);
        //     } else {
        //         // If no record exists, create a new one
        //         DeductionUser::create([
        //             'user_id' => $this->userId,
        //             'deduction_id' => 8,
        //             'amount' => $whtAmount,
        //             'frequency' => 1,
        //             'active_status' => 1
        //         ]);
        //     }
        // }
        
    }

    public function addIndividualAttendance()
    {
        $this->daysRendered = $this->firstHalfDaysRendered + $this->secondHalfDaysRendered;

        $checkDupes = Attendance::where('user_id', $this->userId)
            ->where('start_date', $this->startDate)
            ->where('end_date', $this->endDate)
            ->get();
        
        if($checkDupes->isEmpty()){
            Attendance::create([
                'user_id' => $this->userId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'days_rendered' => $this->daysRendered,
                'first_half' => $this->firstHalfDaysRendered,
                'second_half' => $this->secondHalfDaysRendered
            ]);

        // $this->dispatchBrowserEvent('closeModal', ['id' => 'addIndividualAttendanceModal']);
        $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully saved!']);
        
        }else{
            if($this->daysRendered > 0){
                $selectAttendanceUser = Attendance::findOrFail($checkDupes[0]->id);

                $selectAttendanceUser->days_rendered = $this->firstHalfDaysRendered + $this->secondHalfDaysRendered;
                $selectAttendanceUser->first_half = $this->firstHalfDaysRendered;
                $selectAttendanceUser->second_half = $this->secondHalfDaysRendered;
                $selectAttendanceUser->save();
    
                $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Attendance record updated!']);
            }else{
                $this->deleteAttendance();
                // $this->dispatchBrowserEvent('fireToast', ['icon' => 'error', 'title' => 'Attendance record removed!']);
            }
        }

        $this->AutoAddDeduction();

        $this->emit('refreshProcessPayrollJobOrderComponent');
    }



    public function deleteAttendance(){
        $checkDupes = Attendance::where('user_id', $this->userId)
        ->where('start_date', $this->startDate)
        ->where('end_date', $this->endDate)
        ->get();

        if($checkDupes->isNotEmpty()){
            $selectAttendance = Attendance::findOrFail($checkDupes[0]->id)->delete();
            $this->daysRendered = '0.000';
            $this->firstHalfDaysRendered = '0.000';
            $this->secondHalfDaysRendered = '0.000';
            
            // $this->dispatchBrowserEvent('fireToast', ['error' => 'danger', 'title' => 'Attendance record removed!']);
        }
        $this->emit('refreshProcessPayrollJobOrderComponent');
    }

    public function render()
    {
        $checkAttendance = Attendance::where('user_id', $this->userId)
        ->where('start_date', $this->startDate)
        ->where('end_date', $this->endDate)
        ->get();
        if($checkAttendance->isNotEmpty()){
            $this->daysRendered = $checkAttendance[0]->days_rendered;
            $this->firstHalfDaysRendered = $checkAttendance[0]->first_half;
            $this->secondHalfDaysRendered = $checkAttendance[0]->second_half;
        }else{
            $this->daysRendered = '0.000';
            $this->firstHalfDaysRendered = '0.000';
            $this->secondHalfDaysRendered = '0.000';
        }
        return view('livewire.modals.add-individual-attendance-modal');
    }
}

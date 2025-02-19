<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\NewPayrollIndex;
use App\Models\NewPayrollIndexAllDed;
use Carbon\Carbon;
use App\Models\Deduction;
use App\Models\Allowance;
use PDF;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use function PHPUnit\Framework\isEmpty;

class ProcessPayslipsComponent extends Component
{

    public $payrollDateFrom, $payrollDateTo, $loadingTxt = "", $loadingProgress = 0, $loadingProgressMax = 10, $loadingState = false, $isLessFifteen = 'full_month';

    public function updateLoadingProgress()
    {
        $this->loadingProgress += 1;
        $this->loadingProgress = round(($this->loadingProgress / $this->loadingProgressMax) * 100, 2);
    }

    public function generatePayslip(){
        set_time_limit(0);
        $this->loadingState = true;
        //------------------ PREVIEWING PDF-----------------
            $oMerger = PDFMerger::init();
            if($this->isLessFifteen == 'full_month'){
                $employees = NewPayrollIndex::where(function($query) {
                    $query->whereBetween('period_covered_from', [$this->payrollDateFrom, $this->payrollDateTo])
                        ->orWhereBetween('period_covered_to', [$this->payrollDateFrom, $this->payrollDateTo]);
                    })
                    ->with('newPayrollIndexAllDed')
                    ->get()
                    ->sortByDesc('period_covered_from')
                    ->groupBy('user_id');
            }else if($this->isLessFifteen == 'less_fifteen_first_half'){
                    $employees = NewPayrollIndex::where(function($query) {
                        $query->whereBetween('period_covered_from', [$this->payrollDateFrom, $this->payrollDateTo])
                            ->orWhereBetween('period_covered_to', [$this->payrollDateFrom, $this->payrollDateTo]);
                        })
                        ->where('first_half','<>', 0.000)
                        ->where('second_half','=', 0.000)
                        ->with('newPayrollIndexAllDed')
                        ->get()
                        ->sortByDesc('period_covered_from')
                        ->groupBy('user_id');
            }else{
                    $employees = NewPayrollIndex::where(function($query) {
                        $query->whereBetween('period_covered_from', [$this->payrollDateFrom, $this->payrollDateTo])
                            ->orWhereBetween('period_covered_to', [$this->payrollDateFrom, $this->payrollDateTo]);
                        })
                        ->where('first_half','=', 0.000)
                        ->where('second_half','<>', 0.000)
                        ->with('newPayrollIndexAllDed')
                        ->get()
                        ->sortByDesc('period_covered_from')
                        ->groupBy('user_id');
            }

            if($employees->isEmpty()){
                // dd('NO DATA SELECTED');
            }

            $deductions = Deduction::all();
            $allowances = Allowance::all();

            $this->loadingProgressMax = count($employees);

                foreach($employees as $employee){
                    $this->updateLoadingProgress();
                    
                    $filename = $employee[0]->user->employee_id . '_' . $this->payrollDateFrom . ' to ' . $this->payrollDateTo . "_" . $employee[0]->name . ".pdf";

                    if(round($this->loadingProgress, 2) > 99){
                        $this->loadingTxt = 'Finished generating payslips!';
                    }else{
                        $this->loadingTxt = 'Generating ' . $filename . '...';
                    }

                    foreach($employee as $employeeData){
                        $employeeData->total_user_deduction = 0.00;
                        $employeeData->total_user_allowance = 0.00;

                        $employeeDeductions = $employeeData->newPayrollIndexAllDed()->get();
                        $employeeDeductions = collect($employeeDeductions->where('npiad_type', 'DEDUCTION'))->sortBy('npiad_sort_position')->groupBy('npiad_group');
                        $employeeData->user_deductions = $employeeDeductions;
                                                                                                        //->where('status', 'ACTIVE', 'or')
                        $user_deductions_per_deduction = $employeeData->newPayrollIndexAllDed()->get();
                        $user_deductions_per_deduction = collect($user_deductions_per_deduction)->sortBy('npiad_sort_position')->groupBy('npiad_type');
                        $employeeData->user_deductions_per_deduction = $user_deductions_per_deduction;


                        $employeeAllowances = $employeeData->newPayrollIndexAllDed()->get();
                        $employeeAllowances = collect($employeeAllowances->where('npiad_type', 'ALLOWANCE'))->sortBy('npiad_sort_position')->groupBy('npiad_group');
                        $employeeData->user_allowances = $employeeAllowances;
                                                                                                        //->where('status', 'ACTIVE', 'or')
                        $user_allowances_per_allowance = $employeeData->newPayrollIndexAllDed()->get();
                        $user_allowances_per_allowance = collect($user_allowances_per_allowance)->sortBy('npiad_sort_position')->groupBy('npiad_type');
                        $employeeData->user_allowances_per_allowance = $user_allowances_per_allowance;


                        foreach($employeeDeductions as $eDeductions){
                            foreach($eDeductions as $deduction){
                                $employeeData->total_user_deduction += $deduction->npiad_amount;
                            }
                        }

                        foreach($employeeAllowances as $eAllowances){
                            foreach($eAllowances as $allowance){
                                $employeeData->total_user_allowance += $allowance->npiad_amount;
                            }
                        }
                    }

                    $data = [
                        'employee' => $employee,
                        'joDeductions' => $deductions,
                        'joAllowances' => $allowances,
                    ];
        
                    $pdf = PDF::loadView('payslip-template-jo', $data);
                    $dompdf = $pdf->getDomPDF();
                    $dompdf->set_option('compress', true);
                    $dompdf->getOptions()->setIsFontSubsettingEnabled(true);
                    $pdf->set_option('isFontSubsettingEnabled', true);

                    $trimmedFilename = preg_replace('/\s+/', ' ', $filename);
            
                    $pdf->set_option("isPhpEnabled", true);
                    $path = storage_path('app/payslips/' . $trimmedFilename);
                    
                    $pdf->stream($trimmedFilename, ['compress' => 1]);
                    file_put_contents($path, $pdf->output());
                }

    }

    public function render()
    {
        return view('livewire.process-payslips-component');
    }
}

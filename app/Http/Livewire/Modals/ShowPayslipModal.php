<?php

namespace App\Http\Livewire\Modals;

use App\Models\NewPayrollIndex;
use App\Models\NewPayrollIndexAllDed;
use App\Models\User;
use App\Models\Deduction;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
// use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use PDF;
// use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class ShowPayslipModal extends Component
{
    public $payrollIndex,
            $payrollIndexUserPerYear,
            $payrollIndexPerUser = "",
            $userId,
            $joDeductions,
            $additionalDeductions,
            $uniqueAdditionalDeductionGroups,
            $pdfPayslip;

    protected $listeners = ['openPayslipModal'];

    public function openPayslipModal($userId)
    {
        $this->userId = $userId;
    }
    public function downloadPayslip($period_from, $period_to){
        $period_from = Carbon::parse($period_from);
        $period_to = Carbon::parse($period_to);

        $user = User::with('agencyUnit.agencySection')->findOrFail($this->userId);
        $filename = $user->employee_id. '_' . $period_from->format('Y-m-d') . ' to ' . $period_to->format('Y-m-d') . '_' . $user->full_name . '.pdf';
        $payslipPath = storage_path('app/payslips/'.$filename);

        // Check if the file exists
        if (file_exists($payslipPath)) {
            // Download the file
            return response()->download($payslipPath, $filename);
        } else {
            // File not found, return response with error message
            $this->dispatchBrowserEvent('fireToast', ['icon' => 'error', 'title' => 'No file detected. <br><br>Please process payslip for the period of: <br><br>' . \Carbon\Carbon::parse($period_from)->format('F d, Y') . ' to ' . \Carbon\Carbon::parse($period_to)->format('F d, Y')]);
        }
        // dd($filename);
    }


    public function render()
    {
        $payrollsByYear = [];
        $payrollsByYearAndMonth = [];

        if(isset($this->userId)){

            // $user = User::findOrFail($this->userId);
            $user = User::findOrFail($this->userId);
            $payrollsByYearAndMonth = $user->newPayrollIndex->groupBy(function ($item) {
                return Carbon::parse($item->period_covered_from)->format('Y'); // Group by year
            })->map(function ($yearGroup) {
                return $yearGroup->groupBy(function ($item, $key) {
                    return Carbon::parse($item->period_covered_from)->format('F'); // Group by month
                    // return [Carbon::create()->month($key)->format('F') => $item];
                }); // Sort months in descending order
            })->sortKeysDesc(); // Sort years in descending order
        }

        return view('livewire.modals.show-payslip-modal', [
            'payrollsByYear' => $payrollsByYearAndMonth,
            'user' => $user
        ]);
    }
}

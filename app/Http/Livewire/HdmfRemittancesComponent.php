<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Deduction;
use App\Models\NewPayrollIndex;
use App\Models\Fund;
use App\Models\AgencySection;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HdmfRemittancesComponent extends Component
{
    public $payrollDateFrom, $payrollDateTo, $loadingTxt = "", $loadingProgress = 0, $loadingState = false, $listOfDeductions, $deduction = 1;

    public function mount()
    {
        $this->listOfDeductions = Deduction::where('deduction_group', 'HDMF')->get();
        $this->listOfDeductions = [
            ['id' => 1, 'description' => 'HDMF Premium'],
            ['id' => 2, 'description' => 'HDMF M2'],
            ['id' => 3, 'description' => 'HDMF MPL'],
            ['id' => 4, 'description' => 'HDMF CL'],
            ['id' => 5, 'description' => 'GSIS'],
        ];
    }

    public function createExcelFile($filterSection = null, $filterFund = null)
    {
        $templatePath = storage_path('app/excel_templates/hdmf_remittance_template.xlsx');
        $spreadsheet = IOFactory::load($templatePath);

        DB::statement("SET SQL_MODE=''"); // Allow GROUP BY
        $deduction = $this->deduction;
        $newPayroll = NewPayrollIndex::with([
            'user.fund',
            'user.agencyUnit.agencySection',
            'newPayrollIndexAllDed'
        ])
            ->where('period_covered_from', $this->payrollDateFrom)
            ->where('period_covered_to', $this->payrollDateTo)
            ->whereHas('newPayrollIndexAllDed', function ($query) use ($deduction) {
                $query->where('npiad_deduction_id', $deduction);
                $query->where('npiad_type', 'DEDUCTION');
            });
        
        if ($filterSection !== null) {
            $newPayroll->where('office', $filterSection);
        }
        
        if ($filterFund !== null) {
            $newPayroll->whereHas('user.fund', function ($query) {
                $query->where('id', $this->filterFund);
            });
        }
        
        $funds = $newPayroll->get()
            ->groupBy(function ($item) {
                return $item->funding_charges ?? 'Unknown Fund';
            })
            ->map(function ($groupedByFund) {
                return $groupedByFund->groupBy(function ($item) {
                    return $item->office; // Office-level grouping
                });
            });

    

        if ($funds->isEmpty()) {
            return collect([]);
        }

        foreach ($funds as $fundName => $offices) {


                // foreach($payrollSection as $section){
                    // Clone the template sheet for each fund


                    foreach($offices as $officeName => $payrollEntries){

                        $templateSheet = $spreadsheet->getSheet(0);
                        $newSheet = clone $templateSheet;
        
                        $sheetName = Str::limit(preg_replace('/[\\\\\\/\\?\\*\\[\\]:]/', '', $fundName ." ". $officeName), 31, '');
                        $newSheet->setTitle($sheetName ?: 'HDMF_PREM');
        
                        // Add the sheet to the spreadsheet
                        $spreadsheet->addSheet($newSheet);
        
                        $totalRemittance = 0;
                        $counter = 0;
        
                        $rowStart = 11; // Start inserting rows at 11

                        foreach($payrollEntries as $npiUser) {

                            $rt = 'DT';
                            $plt = 'MC';
                            $er = 200;
                            $deduction;

                            $deductionRecord = $npiUser->newPayrollIndexAllDed()
                            ?->where('npiad_deduction_id', $deduction)
                            ->first();

                            if ($deduction == 2) {
                                $plt = 'M2';
                                $er = 0; // M2 has no employer remittance
                            } elseif ($deduction == 3) {
                                $plt = 'ST'; // MPL
                                $er = 0; // ST has no employer remittance
                            } elseif ($deduction == 4) {
                                $plt = 'CL'; // CL
                                $er = 0; // CL has no employer remittance
                            }

        
                            $amount = $deductionRecord?->npiad_amount ?? 0;
                            $application_no = $deductionRecord?->npiad_application_no ?? null;

                            // Insert data for each user
                            $newSheet->insertNewRowBefore($rowStart);
                            $newSheet->setCellValue("A{$rowStart}", $rt);
                            $newSheet->setCellValue("B{$rowStart}", $npiUser?->hdmf);
                            $newSheet->setCellValue("C{$rowStart}", $application_no);
                            $newSheet->setCellValue("D{$rowStart}", $npiUser?->last_name);
                            $newSheet->setCellValue("E{$rowStart}", '=TRIM("' . $npiUser?->first_name . ' ' . $npiUser?->name_extn . '")');
                            $newSheet->setCellValue("F{$rowStart}", $npiUser?->npiad_middle_name);
                            $newSheet->setCellValue("G{$rowStart}", $amount);
                            $newSheet->setCellValue("H{$rowStart}", $er);
                            $newSheet->setCellValue("I{$rowStart}", $plt);

                            $formattedDate = Carbon::parse($npiUser?->period_covered_to)->format('Ym');
                            $newSheet->setCellValue("J{$rowStart}", $formattedDate . '01');

                            $totalRemittance += $amount;

                            $rowStart++; // Move to next row
                            $counter++;
                        }

                    $newSheet->setCellValue('C6', $counter);

                    }

                    // Set totals
                    $newSheet->setCellValue('C5', $totalRemittance);

                    // Remove the template row
                    $newSheet->removeRow(10);
                        // }
                    // }
                }

            // Delete the original template sheet
            $spreadsheet->removeSheetByIndex(0);

            $nameConcat = '';

            if($this->deduction == 1) {
                $nameConcat = 'premium';
            } elseif($this->deduction == 2) {
                $nameConcat = 'mp2';
            } elseif($this->deduction == 3) {
                $nameConcat = 'mpl';
            } elseif($this->deduction == 4) {
                $nameConcat = 'cl';
            }

            // Save the Excel file to storage temporarily
            $fileName = 'hdmf_'. $nameConcat .'_' . now()->format('Ymd_His') . '.xlsx';
            $modifiedPath = storage_path("app/hdmf_reports/{$fileName}");
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($modifiedPath);

            // Dispatch browser event to trigger download
            $this->dispatchBrowserEvent('fileDownload', [
                'url' => route('download.remittance', ['filename' => $fileName])
            ]);

            return $modifiedPath; // Optional: return path if you also want to use it later

        }

        // GSIS
    public function createExcelFileGsis($filterSection = null, $filterFund = null)
        {
            $templatePath = storage_path('app/excel_templates/gsis_remittance_template.xlsx');
            $spreadsheet = IOFactory::load($templatePath);
    
            DB::statement("SET SQL_MODE=''"); // Allow GROUP BY
            $deduction = $this->deduction;
            $newPayroll = NewPayrollIndex::with([
                'user.fund',
                'user.agencyUnit.agencySection',
                'newPayrollIndexAllDed'
            ])
                ->where('period_covered_from', $this->payrollDateFrom)
                ->where('period_covered_to', $this->payrollDateTo)
                ->whereHas('newPayrollIndexAllDed', function ($query) use ($deduction) {
                    $query->where('npiad_deduction_id', $deduction);
                    $query->where('npiad_type', 'DEDUCTION');
                });
            
            if ($filterSection !== null) {
                $newPayroll->where('office', $filterSection);
            }
            
            if ($filterFund !== null) {
                $newPayroll->whereHas('user.fund', function ($query) {
                    $query->where('id', $this->filterFund);
                });
            }
            
            $funds = $newPayroll->get()
                ->groupBy(function ($item) {
                    return $item->funding_charges ?? 'Unknown Fund';
                })
                ->map(function ($groupedByFund) {
                    return $groupedByFund->groupBy(function ($item) {
                        return $item->office; // Office-level grouping
                    });
                });
    
        
    
            if ($funds->isEmpty()) {
                return collect([]);
            }
    
            foreach ($funds as $fundName => $offices) {
    
    
                    // foreach($payrollSection as $section){
                        // Clone the template sheet for each fund
    
    
                        foreach($offices as $officeName => $payrollEntries){
    
                            $templateSheet = $spreadsheet->getSheet(0);
                            $newSheet = clone $templateSheet;
            
                            $sheetName = Str::limit(preg_replace('/[\\\\\\/\\?\\*\\[\\]:]/', '', $fundName ." ". $officeName), 31, '');
                            $newSheet->setTitle($sheetName ?: 'GSIS attachment');
            
                            // Add the sheet to the spreadsheet
                            $spreadsheet->addSheet($newSheet);
            
                            $totalRemittance = 0;
                            $totalPs = 0;
                            $totalGs = 0;
                            $totalEc = 0;
                            $totalConsoloan = 0;
                            $totalMplite = 0;
                            $totalEmergency = 0;
                            $totalPl = 0;
                            $totalGfal = 0;
                            $totalMpl = 0;
                            $totalCpl = 0;

                            $counter = 0;
            
                            $rowStart = 7; // Start inserting rows at 7
    
                            foreach($payrollEntries as $npiUser) {
    
                                $deduction;
    
                                $ps = $npiUser->newPayrollIndexAllDed()
                                ?->where('npiad_deduction_id', 9)
                                ->first();

                                $gs = $npiUser?->daily_monthly_rate * .12;

                                $ec = 100;

                                $consoloan = $npiUser->newPayrollIndexAllDed()
                                ?->where('npiad_deduction_id', 10)
                                ->first();

                                $mplite = $npiUser->newPayrollIndexAllDed()
                                ?->where('npiad_deduction_id', 14)
                                ->first();

                                $emergency = $npiUser->newPayrollIndexAllDed()
                                ?->where('npiad_deduction_id', 15)
                                ->first();

                                $pl = $npiUser->newPayrollIndexAllDed()
                                ?->where('npiad_deduction_id', 13)
                                ->first();

                                $gfal = $npiUser->newPayrollIndexAllDed()
                                ?->where('npiad_deduction_id', 16)
                                ->first();

                                $mpl = $npiUser->newPayrollIndexAllDed()
                                ?->where('npiad_deduction_id', 11)
                                ->first();

                                $cpl = $npiUser->newPayrollIndexAllDed()
                                ?->where('npiad_deduction_id', 12)
                                ->first();
    
                                // Insert data for each user
                                $newSheet->insertNewRowBefore($rowStart);
                                $newSheet->setCellValue("A{$rowStart}", $npiUser?->gsis);
                                $newSheet->setCellValue("B{$rowStart}", $npiUser?->last_name);
                                $newSheet->setCellValue("C{$rowStart}", '=TRIM("' . $npiUser?->first_name .'")');
                                $newSheet->setCellValue("D{$rowStart}", $npiUser?->npiad_middle_name);
                                $newSheet->setCellValue("F{$rowStart}", $npiUser?->name_extn);
                                $newSheet->setCellValue("G{$rowStart}", $npiUser?->birthdate); // birthdate
                                $newSheet->setCellValue("H{$rowStart}", $npiUser?->gsis_crn ?? 'NO CRN'); // CRN
                                $newSheet->setCellValue("I{$rowStart}", $npiUser?->daily_monthly_rate); // Monthly Salary
                                $newSheet->setCellValue("J{$rowStart}", 'wala pa'); // Effectivity Date
                                $newSheet->setCellValue("K{$rowStart}", $ps?->npiad_amount ?? 0); // PS
                                $newSheet->setCellValue("L{$rowStart}", $gs);
                                $newSheet->setCellValue("M{$rowStart}", $ec);
                                $newSheet->setCellValue("N{$rowStart}", $consoloan?->npiad_amount ?? 0);
                                $newSheet->setCellValue("O{$rowStart}", $mplite?->npiad_amount ?? 0);
                                $newSheet->setCellValue("P{$rowStart}", $emergency?->npiad_amount ?? 0);
                                $newSheet->setCellValue("Q{$rowStart}", $pl?->npiad_amount ?? 0);
                                $newSheet->setCellValue("R{$rowStart}", $gfal?->npiad_amount ?? 0);
                                $newSheet->setCellValue("S{$rowStart}", $mpl?->npiad_amount ?? 0);
                                $newSheet->setCellValue("T{$rowStart}", $cpl?->npiad_amount ?? 0);
    
                    
                                $totalPs += $ps?->npiad_amount ?? 0;
                                $totalGs += $gs;
                                $totalEc += $ec;
                                $totalConsoloan += $consoloan?->npiad_amount ?? 0;
                                $totalMplite += $mplite?->npiad_amount ?? 0;
                                $totalEmergency += $emergency?->npiad_amount ?? 0;
                                $totalPl += $pl?->npiad_amount ?? 0;
                                $totalGfal += $gfal?->npiad_amount ?? 0;
                                $totalMpl += $mpl?->npiad_amount ?? 0;
                                $totalCpl += $cpl?->npiad_amount ?? 0;

                                $totalRemittance += ($ps?->npiad_amount ?? 0) + $gs + $ec + ($consoloan?->npiad_amount ?? 0) + ($mplite?->npiad_amount ?? 0) + ($emergency?->npiad_amount ?? 0) + ($pl?->npiad_amount ?? 0) + ($gfal?->npiad_amount ?? 0) + ($mpl?->npiad_amount ?? 0) + ($cpl?->npiad_amount ?? 0);
    
                                $rowStart++; // Move to next row
                                $counter++;
                            }


                            $newSheet->setCellValue('K'. $rowStart, $totalPs);
                            $newSheet->setCellValue('L'. $rowStart, $totalGs);
                            $newSheet->setCellValue('M'. $rowStart, $totalEc);
                            $newSheet->setCellValue('N'. $rowStart, $totalConsoloan);
                            $newSheet->setCellValue('O'. $rowStart, $totalMplite);
                            $newSheet->setCellValue('P'. $rowStart, $totalEmergency);
                            $newSheet->setCellValue('Q'. $rowStart, $totalPl);
                            $newSheet->setCellValue('R'. $rowStart, $totalGfal);
                            $newSheet->setCellValue('S'. $rowStart, $totalMpl);
                            $newSheet->setCellValue('T'. $rowStart, $totalCpl);


                            $formattedDate = Carbon::parse($npiUser?->period_covered_to)->format('m/Y');
                            $newSheet->setCellValue("B3", $formattedDate);
                            // Set totals
                            $newSheet->setCellValue('N'. $rowStart + 18, $totalRemittance);
        
                            // Remove the template row
                            $newSheet->removeRow(6);
    
                        }

    
                            // }
                        // }
                    }
    
                // Delete the original template sheet
                $spreadsheet->removeSheetByIndex(0);
    
                // Save the Excel file to storage temporarily
                $fileName = 'gsis_remittance' . now()->format('Ymd_His') . '.xlsx';
                $modifiedPath = storage_path("app/gsis_reports/{$fileName}");
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($modifiedPath);
    
                // Dispatch browser event to trigger download
                $this->dispatchBrowserEvent('fileDownload', [
                    'url' => route('download.remittance', ['filename' => $fileName])
                ]);
    
                return $modifiedPath; // Optional: return path if you also want to use it later
    
            }

    

    public function generateRemittanceTemplate()
    {
        if ($this->deduction > 0 && $this->deduction <= 4){
            $this->createExcelFile();
        } else if ($this->deduction >= 5 ) {
            $this->createExcelFileGsis();
        }
    }


    public function render()
    {
        return view('livewire.hdmf-remittances-component');
    }
}

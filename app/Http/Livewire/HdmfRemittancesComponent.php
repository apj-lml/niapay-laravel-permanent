<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Deduction;
use App\Models\NewPayrollIndex;
use App\Models\Fund;
use App\Models\AgencySection;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Font;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Shared\Html;

use Carbon\Carbon;
use Illuminate\Support\Str;

use ZipArchive;

class HdmfRemittancesComponent extends Component
{
    public $payrollDateFrom, $payrollDateTo, $loadingTxt = "", $loadingProgress = 0, $loadingState = false, $listOfDeductions, $listOfGsisOfficeCodes, $listOfBirAccounts, $listOfHdmfEmployerNo, $deduction = 1, $isLessFifteen = 'full_month';

    public function mount()
    {
        $this->listOfDeductions = Deduction::where('deduction_group', 'HDMF')->get();
        $this->listOfDeductions = [
            ['id' => 1, 'description' => 'HDMF Premium'],
            ['id' => 2, 'description' => 'HDMF M2'],
            ['id' => 3, 'description' => 'HDMF MPL'],
            ['id' => 4, 'description' => 'HDMF CL'],
            //5 to 7 used to be GSIS
            ['id' => 5, 'description' => 'GSIS'],
            ['id' => 8, 'description' => 'WHTAX'],
            // ['id' => 9, 'description' => 'COOP'],
        ];

        //This is not used yet
        $this->listOfGsisOfficeCodes = [
            ['PIMO'   => '1000020661'],
            ['ASRIS'  => '1000020661'],
            ['SFDRIS' => '1000020661'],
            ['CARP'   => '1000020661'],
            ['LARIS'  => 'N/A'],
            ['ADRIS'  => 'N/A'],
        ];

        //This is not used yet
        $this->listOfBirAccounts = [
            ['PIMO'   => '000 916 415 050'],
            ['ASRIS'  => '000 916 415 051'],
            ['SFDRIS' => '000 916 415 042'],
            ['CARP'   => '000 916 415 174'],
            ['LARIS'  => 'N/A'],
            ['ADRIS'  => 'N/A'],
        ];

    }

    // HDMF that generates based on historical data AKA NPI and NPIAD
    public function createExcelFileOrig($filterSection = null, $filterFund = null)
    {
        $templatePath = storage_path('app/excel_templates/hdmf_remittance_template.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $isBelowFifteen = 0;

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
                // dd($item);
                // Combine funding_charges and acct_no for grouping
                return ($item->funding_charges ?? 'Unknown Fund') . '|' . ($item->fund_acct_no ?? 'N/A');
            })
            ->map(function ($groupedByFund) {
                return $groupedByFund->groupBy(function ($item) {
                    return $item->office; // Office-level grouping
                });
            });

    

        if ($funds->isEmpty()) {
            return collect([]);
        }

        foreach ($funds as $fundKey  => $offices) {

            [$fundName, $fundAcctNo] = explode('|', $fundKey);

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
                        $totalEe= 0;
                        $totalEr= 0;
                        $counter = 0;
        
                        $rowStart = 11; // Start inserting rows at 11

                        foreach($payrollEntries->sortBy(['last_name', 'first_name']) as $npiUser) {

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
                            $newSheet->setCellValue("F{$rowStart}", $npiUser?->middle_name);
                            $newSheet->setCellValue("G{$rowStart}", $amount);
                            $newSheet->setCellValue("H{$rowStart}", $er);
                            $newSheet->setCellValue("I{$rowStart}", $plt);

                            $formattedDate = Carbon::parse($npiUser?->period_covered_to)->format('Ym');
                            $newSheet->setCellValue("J{$rowStart}", $formattedDate . '01');
                            
                            $totalEe += $amount;

                            $totalEr += $er;

                            $totalRemittance += $amount + $er;

                            $rowStart++; // Move to next row
                            $counter++;
                        }

                    $newSheet->setCellValue('C5', $totalRemittance);

                    $newSheet->setCellValue('C6', $counter);

                    $newSheet->setCellValue('C7', $fundName ." / ". $fundAcctNo);

                    // Remove the template row

                    $newSheet->setCellValue('G5', $totalEe);
                    $newSheet->setCellValue('H5', $totalEr);

                    $newSheet->setCellValue('G2', "FUND " . $fundName);


                    $newSheet->removeRow(10);
                    }

                    // Set totals

                    
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

    //HDMF that generates based on Current Deduction
    public function createExcelFile($filterSection = null, $filterFund = null)
    {
        $templatePath = storage_path('app/excel_templates/hdmf_remittance_template.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $isBelowFifteen = 0;

        if($this->isLessFifteen != 'full_month') {
            $isBelowFifteen = 1;
        }

        DB::statement("SET SQL_MODE=''"); // Allow GROUP BY
        $deduction = $this->deduction;
      
        $newPayroll = User::with([
            'fund',
            'agencyUnit.agencySection',
            'employeeDeductions' => function ($query) use ($deduction) {
                $query->where('deductions.deduction_group', 'HDMF')
                      ->where('deductions.id', $deduction);
            }
        ])
        ->where('include_to_payroll', 1)
        ->where('is_active', 1)
        ->where('is_less_fifteen', $isBelowFifteen)
        ->whereHas('employeeDeductions', function ($query) use ($deduction) {
            $query->where('deduction_group', 'HDMF')
                  ->where('deductions.id', $deduction);
        });
        

        // dd($newPayroll->get());

        if ($filterSection !== null) {
            $newPayroll->whereHas('agencyUnit.agencySection', function ($q) use ($filterSection) {
                $q->where('name', $filterSection); 
            });
        }
        
        if ($filterFund !== null) {
            $newPayroll->whereHas('fund', function ($q) use ($filterFund) {
                $q->where('id', $filterFund);
            });
        }

        $funds = $newPayroll->get()
        ->groupBy(fn($user) => 
            ($user->fund->fund_description ?? 'Unknown Fund') . '|' . ($user->fund->acct_no ?? 'N/A')
        )
        ->map(fn($users) => 
            $users->groupBy(fn($user) => 
                $user->agencyUnit->agencySection->office ?? 'Unknown Office'
            )
        );
        // $funds = $newPayroll->get()
        // ->groupBy(function ($item) {
        //     // dd($item);
        //     // Combine funding_charges and acct_no for grouping
        //     return ($item->fund->fund_description ?? 'Unknown Fund') . '|' . ($item->fund->acct_no ?? 'N/A');
        // })
        // ->map(function ($groupedByFund) {
        //     return $groupedByFund->groupBy(function ($item) {
        //         return $item->office; // Office-level grouping
        //     });
        // });
        

    

        if ($funds->isEmpty()) {
            return collect([]);
        }

        foreach ($funds as $fundKey  => $offices) {
            [$fundName, $fundAcctNo] = explode('|', $fundKey);
            // dd($fundAcctNo);

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
                        $totalEe= 0;
                        $totalEr= 0;
                        $counter = 0;
        
                        $rowStart = 11; // Start inserting rows at 11

                        foreach($payrollEntries->sortBy(['last_name', 'first_name']) as $npiUser) {

                            $rt = 'DT';
                            $plt = 'MC';
                            $er = 200;
                            $deduction;

                            $deductionRecord = $npiUser->employeeDeductions()
                            ?->where('deductions.id', $deduction)
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
        
                            $amount = $deductionRecord?->pivot?->amount ?? 0;
                            $application_no = $deductionRecord?->pivot?->application_no ?? null;

                            // Insert data for each user
                            $newSheet->insertNewRowBefore($rowStart);
                            $newSheet->setCellValue("A{$rowStart}", $rt);
                            $newSheet->setCellValue("B{$rowStart}", $npiUser?->hdmf);
                            $newSheet->setCellValue("C{$rowStart}", $application_no);
                            $newSheet->setCellValue("D{$rowStart}", $npiUser?->last_name);
                            $newSheet->setCellValue("E{$rowStart}", '=TRIM("' . $npiUser?->first_name . ' ' . $npiUser?->name_extn . '")');
                            $newSheet->setCellValue("F{$rowStart}", $npiUser?->middle_name);
                            $newSheet->setCellValue("G{$rowStart}", $amount);
                            $newSheet->setCellValue("H{$rowStart}", $er);
                            $newSheet->setCellValue("I{$rowStart}", $plt);

                            $formattedDate = Carbon::parse($npiUser?->period_covered_to)->format('Ym');
                            $newSheet->setCellValue("J{$rowStart}", $formattedDate . '01');
                            
                            $totalEe += $amount;

                            $totalEr += $er;

                            $totalRemittance += $amount + $er;

                            $rowStart++; // Move to next row
                            $counter++;
                        }

                    $newSheet->setCellValue('C5', $totalRemittance);

                    $newSheet->setCellValue('C6', $counter);

                    $newSheet->setCellValue('C7', $fundName ." / ". $fundAcctNo);

                    // Remove the template row

                    $newSheet->setCellValue('G5', $totalEe);
                    $newSheet->setCellValue('H5', $totalEr);

                    $newSheet->setCellValue('G2', "FUND " . $fundName);


                    $newSheet->removeRow(10);
                    }

                    // Set totals

                    
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

    // GSIS that generates based on historical data AKA NPI and NPIAD
    public function createExcelFileGsisOrig($filterSection = null, $filterFund = null)
    {
            ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

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
                    $query->where('npiad_group', 'GSIS');
                    $query->where('npiad_type', 'DEDUCTION');
                });

            // dd($newPayroll->get());
            
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
    
                            foreach($payrollEntries->sortBy(['last_name', 'first_name']) as $npiUser) {
    
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
                                $newSheet->setCellValue("D{$rowStart}", $npiUser?->middle_name);
                                $newSheet->setCellValue("E{$rowStart}", $npiUser?->name_extn);
                                $newSheet->setCellValue("F{$rowStart}", $npiUser?->birthdate); // birthdate
                                $newSheet->setCellValue("G{$rowStart}", $npiUser?->gsis_crn ?? 'NO CRN'); // CRN
                                $newSheet->setCellValue("H{$rowStart}", $npiUser?->daily_monthly_rate); // Monthly Salary
                                // $newSheet->setCellValue("J{$rowStart}", 'wala pa'); // Effectivity Date
                                $newSheet->setCellValue("I{$rowStart}", $ps?->npiad_amount ?? 0); // PS
                                $newSheet->setCellValue("J{$rowStart}", $gs);
                                $newSheet->setCellValue("K{$rowStart}", $ec);
                                $newSheet->setCellValue("L{$rowStart}", $consoloan?->npiad_amount ?? 0);
                                $newSheet->setCellValue("M{$rowStart}", $mplite?->npiad_amount ?? 0);
                                $newSheet->setCellValue("N{$rowStart}", $emergency?->npiad_amount ?? 0);
                                $newSheet->setCellValue("O{$rowStart}", $pl?->npiad_amount ?? 0);
                                $newSheet->setCellValue("P{$rowStart}", $gfal?->npiad_amount ?? 0);
                                $newSheet->setCellValue("Q{$rowStart}", $mpl?->npiad_amount ?? 0);
                                $newSheet->setCellValue("R{$rowStart}", $cpl?->npiad_amount ?? 0);
    
                    
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


                            $newSheet->setCellValue('I'. $rowStart, $totalPs);
                            $newSheet->setCellValue('J'. $rowStart, $totalGs);
                            $newSheet->setCellValue('K'. $rowStart, $totalEc);
                            $newSheet->setCellValue('L'. $rowStart, $totalConsoloan);
                            $newSheet->setCellValue('M'. $rowStart, $totalMplite);
                            $newSheet->setCellValue('N'. $rowStart, $totalEmergency);
                            $newSheet->setCellValue('O'. $rowStart, $totalPl);
                            $newSheet->setCellValue('P'. $rowStart, $totalGfal);
                            $newSheet->setCellValue('Q'. $rowStart, $totalMpl);
                            $newSheet->setCellValue('R'. $rowStart, $totalCpl);


                            $formattedDate = Carbon::parse($npiUser?->period_covered_to)->format('m/Y');
                            $newSheet->setCellValue("B3", $formattedDate);
                            // Set totals
                            // $newSheet->setCellValue('N'. $rowStart + 17, $totalRemittance);

                            $newSheet->setCellValue('M'. $rowStart + 12, "FUND " . $fundName);

        
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

    //GSIS that generates based on Current Deduction
    public function createExcelFileGsis($filterSection = null, $filterFund = null)
    {
            ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

            $templatePath = storage_path('app/excel_templates/gsis_remittance_template.xlsx');
            $spreadsheet = IOFactory::load($templatePath);

            $isBelowFifteen = 0;

            if($this->isLessFifteen != 'full_month') {
                $isBelowFifteen = 1;
            }

            DB::statement("SET SQL_MODE=''"); // Allow GROUP BY
            $deduction = $this->deduction;

            // dd($isBelowFifteen);
            $newPayroll = User::with([
                'fund',
                'agencyUnit.agencySection',
                'employeeDeductions' => function ($query) use ($deduction) {
                    $query->where('deductions.deduction_group', 'GSIS');
                    // ->where('deductions.id', $deduction);
                }
            ])
            ->where('users.include_to_payroll', 1)
            ->where('users.is_active', 1)
            ->where('users.is_less_fifteen', $isBelowFifteen)
            ->whereHas('employeeDeductions', function ($query) use ($deduction) {
                $query->where('deductions.deduction_group', 'GSIS');
                    //   ->where('deductions.id', $deduction);
            });

            // dd($newPayroll->get());

            if ($filterSection !== null) {
                $newPayroll->whereHas('agencyUnit.agencySection', function ($q) use ($filterSection) {
                    $q->where('name', $filterSection); 
                });
            }
            
            if ($filterFund !== null) {
                $newPayroll->whereHas('fund', function ($q) use ($filterFund) {
                    $q->where('id', $filterFund);
                });
            }

           $funds = $newPayroll->get()
            ->groupBy(fn($user) => $user->fund->fund_description ?? 'Unknown Fund' . '|' . $user->fund->acct_no ?? 'N/A')
            ->map(fn($users) => 
                $users->groupBy(fn($user) => 
                    $user->agencyUnit->agencySection->office ?? 'Unknown Office'));
    
            if ($funds->isEmpty()) {
                return collect([]);
            }

    
            foreach ($funds as $fundName => $offices) {

                        // [$fundName, $fundAcctNo] = explode('|', $fundKey);
                        $firstUser = $offices->first()?->first(); // get first office → first user
                        $fundAcctNo = $firstUser?->fund?->acct_no;
                        $employment_status = $firstUser->employment_status;
                        // $officeNameForADA = $firstUser?->agencyUnit->agencySection->office;
                        // $amountADA = 0.00;

                       // Create BUR
                        $templateSheetBUR = $spreadsheet->getSheetByName("BUR Template");
           
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
                            $amountADA = 0.00;


                            $counter = 0;
            
                            $rowStart = 7; // Start inserting rows at 7

                            $employees = '';
                            if($payrollEntries->count() > 0){
                                $employees = $payrollEntries[0]->full_name . ' et al.';
                            }else{
                                $employees = $payrollEntries[0]->full_name;
                            }
    
                            foreach($payrollEntries->sortBy(['last_name', 'first_name']) as $npiUser) {
                                // dd($npiUser->employeeDeductions()->where('deductions.id', 9)->first()->pivot->amount);
                           
                                $deduction;
    
                                $ps = $npiUser->employeeDeductions()
                                ?->where('deductions.id', 9)
                                ->first();

                                $gs = $npiUser?->monthly_rate * .12;

                                $ec = 100;

                                $consoloan = $npiUser->employeeDeductions()
                                ?->where('deductions.id', 10)
                                ->first();

                                $mplite = $npiUser->employeeDeductions()
                                ?->where('deductions.id', 14)
                                ->first();

                                $emergency = $npiUser->employeeDeductions()
                                ?->where('deductions.id', 15)
                                ->first();

                                $pl = $npiUser->employeeDeductions()
                                ?->where('deductions.id', 13)
                                ->first();

                                $gfal = $npiUser->employeeDeductions()
                                ?->where('deductions.id', 16)
                                ->first();

                                $mpl = $npiUser->employeeDeductions()
                                ?->where('deductions.id', 11)
                                ->first();

                                $cpl = $npiUser->employeeDeductions()
                                ?->where('deductions.id', 12)
                                ->first();
           
    
                                // Insert data for each user
                                $newSheet->insertNewRowBefore($rowStart);
                                $newSheet->setCellValue("A{$rowStart}", $npiUser?->gsis);
                                $newSheet->setCellValue("B{$rowStart}", $npiUser?->last_name);
                                $newSheet->setCellValue("C{$rowStart}", '=TRIM("' . $npiUser?->first_name .'")');
                                $newSheet->setCellValue("D{$rowStart}", $npiUser?->middle_name);
                                $newSheet->setCellValue("E{$rowStart}", $npiUser?->name_extn);
                                $newSheet->setCellValue("F{$rowStart}", $npiUser?->birthdate); // birthdate
                                $newSheet->setCellValue("G{$rowStart}", $npiUser?->gsis_crn ?? 'NO CRN'); // CRN
                                $newSheet->setCellValue("H{$rowStart}", $npiUser?->monthly_rate); // Monthly Salary
                                // $newSheet->setCellValue("J{$rowStart}", 'wala pa'); // Effectivity Date
                                $newSheet->setCellValue("I{$rowStart}", $ps?->pivot->amount ?? 0); // PS
                                $newSheet->setCellValue("J{$rowStart}", $gs);
                                $newSheet->setCellValue("K{$rowStart}", $ec);
                                $newSheet->setCellValue("L{$rowStart}", $consoloan?->pivot->amount ?? 0);
                                $newSheet->setCellValue("M{$rowStart}", $mplite?->pivot->amount ?? 0);
                                $newSheet->setCellValue("N{$rowStart}", $emergency?->pivot->amount ?? 0);
                                $newSheet->setCellValue("O{$rowStart}", $pl?->pivot->amount ?? 0);
                                $newSheet->setCellValue("P{$rowStart}", $gfal?->pivot->amount ?? 0);
                                $newSheet->setCellValue("Q{$rowStart}", $mpl?->pivot->amount ?? 0);
                                $newSheet->setCellValue("R{$rowStart}", $cpl?->pivot->amount ?? 0);
    
                    
                                $totalPs += $ps?->pivot->amount ?? 0;
                                $totalGs += $gs;
                                $totalEc += $ec;
                                $totalConsoloan += $consoloan?->pivot->amount ?? 0;
                                $totalMplite += $mplite?->pivot->amount ?? 0;
                                $totalEmergency += $emergency?->pivot->amount ?? 0;
                                $totalPl += $pl?->pivot->amount ?? 0;
                                $totalGfal += $gfal?->pivot->amount ?? 0;
                                $totalMpl += $mpl?->pivot->amount ?? 0;
                                $totalCpl += $cpl?->pivot->amount ?? 0;

                                $totalRemittance += ($ps?->pivot->amount ?? 0) + $gs + $ec + ($consoloan?->pivot->amount ?? 0) + ($mplite?->pivot->amount ?? 0) + ($emergency?->pivot->amount ?? 0) + ($pl?->pivot->amount ?? 0) + ($gfal?->pivot->amount ?? 0) + ($mpl?->pivot->amount ?? 0) + ($cpl?->pivot->amount ?? 0);
    
                                $rowStart++; // Move to next row
                                $counter++;
                            }

                            $newSheet->setCellValue('I'. $rowStart, $totalPs);
                            $newSheet->setCellValue('J'. $rowStart, $totalGs);
                            $newSheet->setCellValue('K'. $rowStart, $totalEc);
                            $newSheet->setCellValue('L'. $rowStart, $totalConsoloan);
                            $newSheet->setCellValue('M'. $rowStart, $totalMplite);
                            $newSheet->setCellValue('N'. $rowStart, $totalEmergency);
                            $newSheet->setCellValue('O'. $rowStart, $totalPl);
                            $newSheet->setCellValue('P'. $rowStart, $totalGfal);
                            $newSheet->setCellValue('Q'. $rowStart, $totalMpl);
                            $newSheet->setCellValue('R'. $rowStart, $totalCpl);

                            $formattedDate = Carbon::parse($this->payrollDateTo)->format('m/Y');
                            $newSheet->setCellValue("B3", $formattedDate);
                            // Set totals
                            // $newSheet->setCellValue('N'. $rowStart + 17, $totalRemittance);

                            $newSheet->setCellValue('M'. $rowStart + 12, "FUND " . $fundName);

                            // Remove the template row
                            $newSheet->removeRow(6);

                            $this->generateBUR($templateSheetBUR, $spreadsheet, $fundName, $officeName, $totalGs, $totalEc, $employees, $this->payrollDateTo);

                            $payee = 'GSIS';
                            $pay_loan_type = '-';
                            $particular = htmlspecialchars('GSIS Premiums (Personal & Government Shares) & GSIS Loans of '. $employees, ENT_QUOTES | ENT_XML1, 'UTF-8');
                            
  
                            // Generate ADA per office
                            $this->generateADAFile($fundName, $officeName, $fundAcctNo,  $payee, $pay_loan_type, $particular, $employment_status, $this->payrollDateTo, $totalRemittance, $totalRemittance);               
                        }

                        
                            // Compute amount and total
                        
                            // $this->generateADAFile($fundName, $officeNameForADA, $fundAcctNo, $this->payrollDateTo, $amountADA, $amountADA);               
                            // $amountADA = 0.00;


                    }

                // Step 1: Zip all ADA .docx files directly (no nested zip)
                $adaFolder = storage_path('app/ada_reports');
                // $zipPath = $this->zipAllAdaFilesAndCleanup('GSIS', $this->payrollDateTo);

                // Step 2: Generate Excel
                $fileName = 'gsis_remittance_' . now()->format('Ymd_His') . '.xlsx';
                $modifiedPath = storage_path("app/gsis_reports/{$fileName}");
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($modifiedPath);

                // Step 3: Create final ZIP containing:
                //         - all ADA files (already zipped)
                //         - the Excel remittance file
                $zip = new ZipArchive();

                $formattedDate = \Carbon\Carbon::parse($this->payrollDateTo)->format('F_Y');
                $bundleName = "GSIS_Bundle_{$formattedDate}.zip";
                $bundlePath = storage_path("app/gsis_reports/{$bundleName}");

                // Extract ADA .docx files that are already zipped
                // Instead of zipping the ADA zip again, let's merge them into the final zip
                if ($zip->open($bundlePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

                    // 1️⃣ Include all ADA DOCX files directly
                    $adaDocxFiles = glob($adaFolder . '/*.docx');
                    foreach ($adaDocxFiles as $docx) {
                        $zip->addFile($docx, 'ADA/' . basename($docx));
                    }

                    // 2️⃣ Include GSIS Excel file
                    if (file_exists($modifiedPath)) {
                        $zip->addFile($modifiedPath, basename($modifiedPath));
                    }

                    $zip->close();
                }

                // Step 4: Delete ADA .docx files after bundling
                foreach (glob($adaFolder . '/*.docx') as $docx) {
                    @unlink($docx);
                }

                // dd($bundlePath);
                // Step 5: Trigger browser download for the final ZIP
                $this->dispatchBrowserEvent('fileDownload', [
                    'url' => route('download.remittance', ['filename' => basename($bundlePath)])
                ]);

                return $bundlePath;
    
    }


    
    public function generateRemittanceTemplate()
    {
        if ($this->deduction > 0 && $this->deduction <= 4){
            $this->createExcelFile();
        } else if ($this->deduction >= 5 && $this->deduction < 8) {
            $this->createExcelFileGsis();
        } else if ($this->deduction == 8) {
            $this->createExcelFileWhtax();
        } else if ($this->deduction == 9) {
            $this->createExcelFileCoop();
        } else {
            session()->flash('error', 'Invalid deduction type selected.');
        }
    }


    public function createExcelFileWhtax($filterSection = null, $filterFund = null)
    {
        $templatePath = storage_path('app/excel_templates/bir_remittance_template.xlsx');
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
                $query->where('npiad_group', 'TAX');
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
            foreach ($offices as $officeName => $payrollEntries) {
                if ($payrollEntries->isEmpty()) {
                    continue; // Skip if no entries
                }
        
                // Clone template
                $templateSheet = $spreadsheet->getSheetByName("BIR Attachment");
                $newSheet = clone $templateSheet;
        
                // Ensure unique sheet name
                $sheetName = Str::limit(
                    preg_replace('/[\\\\\\/\\?\\*\\[\\]:]/', '', $fundName ." ". $officeName),
                    31,
                    ''
                );

                $newSheet->setTitle($sheetName ?: 'attachment');

                $spreadsheet->addSheet($newSheet);
                
                // dd($spreadsheet->getSheetNames());

                $totalRemittance = 0;
                $counter = 1;
                $rowStart = 10;
        
                foreach ($payrollEntries as $npiUser) {
                    $deductionRecord = $npiUser->newPayrollIndexAllDed()
                        ?->where('npiad_deduction_id', $deduction)
                        ?->where('npiad_group', 'TAX')
                        ->first();
        
                    $amount = $deductionRecord?->npiad_amount ?? 0;
        
                    $newSheet->insertNewRowBefore($rowStart);
                    $newSheet->setCellValue("A{$rowStart}", $counter);
                    $newSheet->mergeCells("B{$rowStart}:C{$rowStart}");
                    $newSheet->setCellValue("B{$rowStart}", trim(preg_replace('/\s+/', ' ', $npiUser?->last_name. ', ' . $npiUser?->first_name . ' ' . $npiUser?->name_extn . ' ' . $npiUser?->middle_name)));
                    $newSheet->setCellValue("D{$rowStart}", $npiUser?->tin);
                    $newSheet->setCellValue("E{$rowStart}", $amount);
        
                    $totalRemittance += $amount;
                    $rowStart++;
                    $counter++;
                }
        
                // Use last $npiUser safely
                if (isset($npiUser)) {
                    $formattedDate = Carbon::parse($npiUser->period_covered_to)->format('F Y');
                    $newSheet->setCellValue('A5', 'For the Month of ' . $formattedDate);
                }
                
                $newSheet->setCellValue('E' . $rowStart, $totalRemittance);

                // Remove the template row
                $newSheet->removeRow(9);
            }
        }
        
        // Remove original template AFTER all clones are added
        $spreadsheet->removeSheetByIndex(0);
        
   
            // Save the Excel file to storage temporarily
            $fileName = 'whtax' . '_' . now()->format('Ymd_His') . '.xlsx';
            $modifiedPath = storage_path("app/bir_reports/{$fileName}");
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($modifiedPath);

            // Dispatch browser event to trigger download
            $this->dispatchBrowserEvent('fileDownload', [
                'url' => route('download.remittance', ['filename' => $fileName])
            ]);

            return $modifiedPath; // Optional: return path if you also want to use it later

        }
 
        public function generateBUR($templateSheetBUR, $spreadsheet, $fundName, $officeName, $totalGs, $totalEc, $employees, $payrollDateTo) {

            $newSheetBUR = clone $templateSheetBUR;

            /// im working on this
            $sheetNameBUR = Str::limit(preg_replace('/[\\\\\\/\\?\\*\\[\\]:]/', '', 'BUR ' . $fundName ." ". $officeName), 31, '');
            $newSheetBUR->setTitle($sheetNameBUR ?: 'BUR');
            
            // Add the sheet to the spreadsheet
            $spreadsheet->addSheet($newSheetBUR);



            // Get the template text
            $templateText = $newSheetBUR->getCell("D19")->getValue();

            // Replace placeholders with identifiable markers
            $templateText = str_replace(
                ['[EMPLOYEES]', '[OFFICE]', '[DATE]'],
                ['{{EMPLOYEES}}', '{{OFFICE}}', '{{DATE}}'],
                $templateText
            );

            $richText = new RichText();

            // Split the text into parts (including the placeholders)
            $parts = preg_split('/(\{\{EMPLOYEES\}\}|\{\{OFFICE\}\}|\{\{DATE\}\})/', $templateText, -1, PREG_SPLIT_DELIM_CAPTURE);

            foreach ($parts as $part) {
                switch ($part) {
                    case '{{EMPLOYEES}}':
                        $run = $richText->createTextRun($employees);
                        $run->getFont()->setBold(true)->setName('Cambria')->setSize(12);
                        break;
                    case '{{OFFICE}}':
                        $run = $richText->createTextRun($officeName);
                        $run->getFont()->setBold(true)->setName('Cambria')->setSize(12);
                        break;
                    case '{{DATE}}':
                        $run = $richText->createTextRun(Carbon::parse($payrollDateTo)->format('F Y'));
                        $run->getFont()->setBold(true)->setName('Cambria')->setSize(12);
                        break;
                    default:
                        // For regular text, still use createTextRun so it can be styled
                        $run = $richText->createTextRun($part);
                        $run->getFont()->setName('Cambria')->setSize(12);
                        break;
                }
            }

            // Write rich text back into cell
            $newSheetBUR->getCell("D19")->setValue($richText);

            $newSheetBUR->setCellValue("N21", $totalGs);
            $newSheetBUR->setCellValue("N22", $totalEc);
        }

    private function preparePages($data)
    {
        $templatePath = storage_path('app/word_templates/ada_template.docx');
        $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
    
        $funds = $data['funds'] ?? [];
        $month = Carbon::parse($data['date'])->format('F Y');
    
        // Clone the block for each employee
        $template->cloneBlock('block_page', count($funds), true, false, [
            'FUND' => $funds,
            'DATE' => array_fill(0, count($funds), $month),
        ]);
    
        return $template;
    }

    // public function generateADA($fundName)
    // {
    //     $templatePath = storage_path('app/word_templates/ada_template.docx');
    //     $template = new TemplateProcessor($templatePath);

    //     $template->setValue('FUND', $fundName);
    //     $template->setValue('ACCT_NO', $fundName);
    //     $template->setValue('DATE', Carbon::parse($this->payrollDateTo)->format('F Y'));
        
    //     // $template->saveAs('output.docx');

    //     $fileName = 'ada' . '_' . now()->format('Ymd_His') . '.docx';

    //     // Define your save path (relative to your project root)
    //     $outputPath = storage_path("app/ada_reports/{$fileName}"); // if using Laravel
    //     // OR
    //     // $outputPath = __DIR__ . '/output/BUR_501_COB.docx'; // plain PHP

    //     // Ensure the folder exists
    //     if (!file_exists(dirname($outputPath))) {
    //         mkdir(dirname($outputPath), 0777, true);
    //     }

    //     // Save the modified file
    //     $template->saveAs($outputPath);

    //      // Dispatch browser event to trigger download
    //      $this->dispatchBrowserEvent('fileDownload', [
    //          'url' => route('download.remittance', ['filename' => $fileName])
    //      ]);

    // }


    public function generateADAFile($fundName, $officeName, $acctNo, $payee, $pay_loan_type, $particular, $employment_status, $date, $amount, $totalAmount)
    {
        $templatePath = storage_path('app/word_templates/ada_template_remittances.docx');
        $template = new TemplateProcessor($templatePath);

        // Format date
        $formattedDate = Carbon::parse($date)->format('F Y'); // e.g. "October 2025"

        // Set variables
        $template->setValue('FUND', $fundName);
        $template->setValue('ACCT_NO', $acctNo);
        $template->setValue('PAYEE', $payee);
        $template->setValue('PAY_LOAN_TYPE', $pay_loan_type);
        $template->setValue('PARTICULAR', $particular);
        $template->setValue('EMPLOYMENT_STATUS', $employment_status);
        $template->setValue('DATE', $formattedDate);
        $template->setValue('AMOUNT', number_format($amount, 2));
        $template->setValue('TOTAL_AMOUNT', number_format($totalAmount, 2));

        // Define save path
        $fileName = 'ada_' . Str::slug($fundName . '_' . $officeName, '_') . '_' . now()->format('Ymd_His') . '.docx';
        $docxPath = storage_path("app/ada_reports/{$fileName}");
        $zipPath  = storage_path("app/ada_reports/{$fileName}.zip");

        $outputPath = storage_path("app/ada_reports/{$fileName}");

        // Ensure folder exists
        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0777, true);
        }

        // Save generated ADA file
        $template->saveAs($outputPath);

        return $outputPath;
    }


    public function zipAllAdaFilesAndCleanup($payee, $date)
        {
            // Define folder path where .docx ADA files are stored
            $adaFolder = storage_path('app/ada_reports');

            if (!file_exists($adaFolder)) {
                return null; // No folder, nothing to zip
            }

            // Collect all .docx files in the folder
            $docxFiles = glob($adaFolder . '/*.docx');
            if (empty($docxFiles)) {
                return null; // Nothing to zip
            }

            // Format the date nicely (e.g., "October 2025")
            $formattedDate = Carbon::parse($date)->format('F Y');

            // Define ZIP file name
            $zipFileName = "ADA_{$payee}_{$formattedDate}.zip";
            $zipFilePath = storage_path("app/ada_reports/{$zipFileName}");

            // Create the ZIP file
            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($docxFiles as $file) {
                    $zip->addFile($file, basename($file)); // add only file name inside ZIP
                }
                $zip->close();
            } else {
                return null; // Failed to create ZIP
            }

            // Delete all .docx files after zipping
            foreach ($docxFiles as $file) {
                @unlink($file);
            }

            return $zipFilePath;
        }



    public function render()
    {
        return view('livewire.hdmf-remittances-component');
    }
}


<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\AgencySection;
use App\Models\User;
use App\Models\Fund;
use App\Models\MidyearBonus;
use App\Models\Signatory;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use PDF;

class MidyearBonusComponent extends Component
{

    public $payrollYear, $userId, $payrollFunds;

    public $year, $mc;

    protected $queryString = ['year', 'mc'];

    protected $listeners = [
        'refreshMybComponent' => '$refresh',
        'createPdf'
    ];

    public function mount()
    {
        $users = User::all('id', 'employee_id', 'last_name', 'first_name', 'middle_name', 'name_extn', 'position', 'sg_jg', 'step', 'daily_rate', 'monthly_rate');
        foreach($users as $user){
            // Add or update the user

            $checkDupeMyb = MidyearBonus::where('year', $this->year)
            ->where('user_id', $user->id)
            ->get();
            if($checkDupeMyb->isNotEmpty()){
                foreach ($checkDupeMyb as $user) {
                    $user->update([
                        'mc' => $this->mc,
                    ]);
                }
            }else{
                $user = MidyearBonus::create(
                    [
                        'mc' => $this->mc,
                        'year' => $this->year,
                        'emp_id' => $user->employee_id,
                        'name' => $user->full_name,
                        'position_title' => $user->position,
                        'daily_rate' => $user->daily_rate,
                        'monthly_rate' => $user->monthly_rate,
                        'mid_year_bonus' => $user->monthly_rate,
                        'total_mid_year_bonus' => $user->monthly_rate,
                        'casab_loan' => null,
                        'net_amount' => $user->monthly_rate,
                        'user_id' => $user->id,
                    ]
                );
            }
        }
    }

    public function processMidYearPayroll($filterSection = null, $filterFund = null){

        DB::statement("SET SQL_MODE=''"); //this is the trick, use it just before your query to be able to GROUP
            $funds = Fund::with(['users' => function ($query) use ($filterSection) {

                if ($filterSection !== null) {
                    $query->whereHas('agencyUnit.agencySection', function ($subQuery) use ($filterSection) {
                        $subQuery->where('office', $filterSection);
                    });
                }
            
            }, 'users.agencyUnit', 'users.agencyUnit.agencySection', 'users.mybs'])
                ->has('users') // Ensure that the Fund has users
                ->get();
            
        if ($filterFund !== null && $filterFund != 0 ) {
            $funds = $funds->where('id', $filterFund);
        }

        
        if($funds->isEmpty()){
            return collect([]);
        }else{
            foreach($funds as $fund){
                // NEW CODE FOR REMOVING EMPTY UNITS/SECTIONS HERE WITH SIGNATORIES AND MYBS
                foreach ($funds as $fund) {
                    $fund->sections = AgencySection::whereHas('users', function ($query) use ($fund) {
                            // $query->where('fund_id', $fund->id)
                            //     ->where('employment_status', 'PERMANENT')
                            //     ->orWhere('employment_status', 'COTERMINOUS')
                            //     ->where('is_active', 1)
                            //     ->where('include_to_payroll', 1);
                            $query->where(function ($q) {
                                    $q->where('employment_status', 'PERMANENT')
                                    ->orWhere('employment_status', 'COTERMINOUS');
                            })->where('is_active', 1)
                                ->where('fund_id', $fund->id)
                                ->where('include_to_payroll', 1);
                        })
                        ->with([
                            'signatories' => function ($query) {
                                $query->whereHas('agencySection', function ($subQuery) {
                                    $subQuery->where('docu', 'yeb');
                                });
                            },
                            'users' => function ($query) use ($fund) {
                                // $query->where('fund_id', $fund->id)
                                // ->where('employment_status', 'PERMANENT')
                                // ->orWhere('employment_status', 'COTERMINOUS')
                                //     ->where('is_active', 1)
                                //     ->where('include_to_payroll', 1);
                                $query->where(function ($q) {
                                    $q->where('employment_status', 'PERMANENT')
                                      ->orWhere('employment_status', 'COTERMINOUS');
                                })->where('is_active', 1)
                                ->where('fund_id', $fund->id)
                                  ->where('include_to_payroll', 1);
                            },
                            'users.mybs' => function ($query) {
                                $query->where('year', '=', $this->year);
                            }
                        ])
                        ->get()
                        ->groupBy('office');
            }


            $total_mid_year_bonus_per_office = 0.00;
            $total_cash_gift_per_office = 0.00;
            $total_casab_loan_per_office = 0.00;

                // Calculate totals per section
                foreach ($fund->sections as $office => $sections) {
                    // $total_mid_year_bonus_per_office = $fund->users(null, false, null, null, $office)->get()->sum(function ($user) {
                    //     return $user->mybs->sum('mid_year_bonus');
                    // });
                
                    // $total_cash_gift_per_office = $fund->users(null, false, null, null, $office)->get()->sum(function ($user) {
                    //     return $user->mybs->sum('cash_gift');
                    // });
                
                    // $total_casab_loan_per_office = $fund->users(null, false, null, null, $office)->get()->sum(function ($user) {
                    //     return $user->mybs->sum('casab_loan');
                    // });

                    $users = $fund->users(null, false, null, null, $office)->get();

                    $total_mid_year_bonus_per_office = $users->sum(fn($user) => $user->mybs->sum('mid_year_bonus'));
                    $total_cash_gift_per_office = $users->sum(fn($user) => $user->mybs->sum('cash_gift'));
                    $total_casab_loan_per_office = $users->sum(fn($user) => $user->mybs->sum('casab_loan'));

                
                    // Add totals to each section under the office group
                    foreach ($sections as $section) {
                        $section->total_mid_year_bonus_per_office = $total_mid_year_bonus_per_office;
                        $section->total_cash_gift_per_office = $total_cash_gift_per_office;
                        $section->total_casab_loan_per_office = $total_casab_loan_per_office;
                    }
                }

            }

            $payrollFunds = $funds;
        }

  
        return $payrollFunds;
    }

    public function createPdf()
    {
        $payrollFunds = $this->processMidYearPayroll();
        $oMerger = PDFMerger::init();
        $signatories = Signatory::where('docu', 'other_bonus')->get();
    
        foreach ($payrollFunds as $payrollFund) {
            $counter = 0;
    
            foreach ($payrollFund->sections as $office => $payrollSection) {
                foreach ($payrollSection as $section) {
    
                    $firstSection = $payrollSection->first();
    
                    $total_mid_year_bonus_per_office = (float) ($firstSection->total_mid_year_bonus_per_office ?? 0);
                    $total_cash_gift_per_office = (float) ($firstSection->total_cash_gift_per_office ?? 0);
                    $total_casab_loan_per_office = (float) ($firstSection->total_casab_loan_per_office ?? 0);
                    $grand_total_mid_year_bonus_per_office = bcdiv($total_mid_year_bonus_per_office + $total_cash_gift_per_office, 1, 2);
                    $net_amount = bcdiv($grand_total_mid_year_bonus_per_office - $total_casab_loan_per_office, 1, 2);
                    
                    // dd($total_mid_year_bonus_per_office);

                    $data = [
                        'payrollSection' => $payrollSection,
                        'payrollFund' => $payrollFund,
                        'year' => $this->year,
                        'office' => $office,
                        'signatories' => $signatories,
                        'counter' => $counter,
                        'total_mid_year_bonus_per_office' => $total_mid_year_bonus_per_office,
                        'total_cash_gift_per_office' => $total_cash_gift_per_office,
                        'grand_total_mid_year_bonus_per_office' => $grand_total_mid_year_bonus_per_office,
                        'total_casab_loan_per_office' => $total_casab_loan_per_office,
                        'net_amount' => $net_amount,
                    ];
    
                    $pdf = PDF::loadView('print-myb-template', $data)->setOption(['dpi' => 80]);
                    $pdf->set_option("isPhpEnabled", true);
    
                    // Get employees per office/section
                    $employeesOfThisSection = [];
                    foreach ($payrollFund->users(null, false, null, null, $office)->get() as $user) {
                        $employeesOfThisSection[] = $user;
                    }
    
                    if (count($employeesOfThisSection) > 0) {
                        $filename = "";
                        $others = 'other';
    
                        if ((count($employeesOfThisSection) - 1) > 2) {
                            $others = 'others';
                        }
    
                        if (count($employeesOfThisSection) == 1) {
                            $filename = $employeesOfThisSection[0]->full_name . ".pdf";
                        } else {
                            $filename = $employeesOfThisSection[0]->first_name[0] . ". " . $employeesOfThisSection[0]->last_name . " " . $employeesOfThisSection[0]->name_extn . " & " . (count($employeesOfThisSection) - 1) . " " . $others . ".pdf";
                        }
    
                        $trimmedFilename = preg_replace('/\s+/', ' ', $filename);
                        $path = storage_path('app/myb/' . $trimmedFilename);
    
                        // Save and add to merger
                        file_put_contents($path, $pdf->output());
                        $oMerger->addPDF($path, 'all');
                    }
    
                    $counter++;
                }
            }
        }
    
        try {
            $oMerger->merge();
            $filename = 'MYB_' . $this->year . '.pdf';
            $path = storage_path('app/myb/' . $filename);
            // file_put_contents($path, $oMerger->output());
    
            // $this->dispatchBrowserEvent('previewMybPdf', ['mypdf' => base64_encode($oMerger->output()), 'toolbar' => '']);

            $mergedPdf = $oMerger->output();
            file_put_contents($path, $mergedPdf);

            $this->dispatchBrowserEvent('previewMybPdf', [
                'mypdf' => base64_encode($mergedPdf),
                'toolbar' => ''
            ]);

        } catch (\Exception $e) {
            // Log or handle error
            // Log::error('Failed to merge PDFs: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $this->payrollFunds = $this->processMidYearPayroll();

        return view('livewire.midyear-bonus-component');
    }
}

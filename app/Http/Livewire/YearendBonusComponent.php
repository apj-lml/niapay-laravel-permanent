<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\AgencySection;
use App\Models\User;
use App\Models\Fund;
use App\Models\YearendBonus;
use App\Models\Signatory;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use PDF;


class YearendBonusComponent extends Component
{
    public $payrollYear, $userId;

    public $year, $mc, $cg, $totalYeb;

    protected $queryString = ['year', 'mc', 'cg'];

    protected $listeners = [
        'refreshYebComponent' => '$refresh',
        'createPdf'
    ];


    public function mount()
    {
        $users = User::all('id', 'employee_id', 'last_name', 'first_name', 'middle_name', 'name_extn', 'position', 'sg_jg', 'step', 'monthly_rate');
        foreach($users as $user){
            // Add or update the user

            $checkDupeYeb = YearendBonus::where('year', $this->year)
            ->where('user_id', $user->id)
            ->get();

            if($checkDupeYeb->isNotEmpty()){
                foreach ($checkDupeYeb as $user) {
                    $user->update([
                        'mc' => $this->mc,
                    ]);
                }
            }else{

                $cleanedCg = str_replace(',', '', $this->cg);
                $user = YearendBonus::create(
                    [
                        'mc' => $this->mc,
                        'year' => $this->year,
                        'emp_id' => $user->employee_id,
                        'name' => $user->full_name,
                        'position_title' => $user->position,
                        'daily_rate' => $user->monthly_rate / 22,
                        'monthly_rate' => $user->monthly_rate,
                        'year_end_bonus' => $user->monthly_rate,
                        'cash_gift' => bcdiv((float) $cleanedCg, 1, 2),
                        'total_year_end_bonus' => ($user->monthly_rate) + (float) $cleanedCg,
                        'casab_loan' => null,
                        'net_amount' => ($user->monthly_rate) + (float) $cleanedCg,
                        'user_id' => $user->id,
                    ]
                );
            }
        }
    }

    public function processYearEndPayroll($filterSection = null, $filterFund = null){

        DB::statement("SET SQL_MODE=''"); //this is the trick, use it just before your query to be able to GROUP
            $funds = Fund::with(['users' => function ($query) use ($filterSection) {

                if ($filterSection !== null) {
                    $query->whereHas('agencyUnit.agencySection', function ($subQuery) use ($filterSection) {
                        $subQuery->where('office', $filterSection);
                    });
                }
            
            }, 'users.agencyUnit', 'users.agencyUnit.agencySection', 'users.yebs'])
                ->has('users') // Ensure that the Fund has users
                ->get();
            
        if ($filterFund !== null && $filterFund != 0 ) {
            $funds = $funds->where('id', $filterFund);
        }

        
        if($funds->isEmpty()){
            return collect([]);
        }else{
            foreach($funds as $fund){
                $fund->sections = AgencySection::with([
                    'signatories' => function ($query) {
                    $query->whereHas('agencySection', function ($subQuery) {
                        $subQuery->where('docu', 'yeb');
                    });
                }, 'users' => function ($query) use ($fund){
                    $query->where('fund_id', '=', $fund->id); 
                    $query->where('employment_status', '=', 'PERMANENT'); 
                    $query->orWhere('employment_status', '=', 'COTERMINOUS'); 
                    $query->where('is_active', '=', 1); 
                    $query->where('include_to_payroll', '=', 1);

                }, 'users.yebs' => function ($query){
                    $query->where('year', '=', $this->year); // Filter yebs by year 2024
                }])->select('*')->get()->groupBy('office');

                $total_year_end_bonus_per_office = 0.00;
                $total_cash_gift_per_office = 0.00;
                $total_casab_loan_per_office = 0.00;

                   // Calculate totals per section

                foreach ($fund->sections as $office => $sections) {
                
                        $total_year_end_bonus_per_office += $fund->users(null, false, null, null, $office)->get()->sum(function ($user) {
                            return $user->yebs->sum('year_end_bonus');
                        });
                
                        $total_cash_gift_per_office += $fund->users(null, false, null, null, $office)->get()->sum(function ($user) {
                            return $user->yebs->sum('cash_gift');
                        });
                
                        $total_casab_loan_per_office += $fund->users(null, false, null, null, $office)->get()->sum(function ($user) {
                            return $user->yebs->sum('casab_loan');
                        });

                        $sections->put('total_year_end_bonus_per_office', $total_year_end_bonus_per_office);
                        $sections->put('total_cash_gift_per_office', $total_cash_gift_per_office);
                        $sections->put('total_casab_loan_per_office', $total_casab_loan_per_office);

                        $total_year_end_bonus_per_office = 0.00;
                        $total_cash_gift_per_office = 0.00;
                        $total_casab_loan_per_office = 0.00;
                    // }
                
                    // Add office-level totals to the sections group

                }


            }


            $payrollFunds = $funds;
        }

                                
    


        return $payrollFunds;
    }

    public function createPdf()
    {
        $payrollFunds = $this->processYearEndPayroll();

        $oMerger = PDFMerger::init();
        $signatories = Signatory::where('docu', 'other_bonus')->get();

        foreach($payrollFunds as $payrollFund){

            $counter = 0;

            foreach($payrollFund->sections as $office => $payrollSection){
                // dd($payrollSection);
                foreach($payrollSection as $section){
                    
                    // dd($payrollSection['total_year_end_bonus_per_office']);
                    $grand_total_year_end_bonus_per_office = bcdiv((float) $payrollSection['total_year_end_bonus_per_office'] + (float) $payrollSection['total_cash_gift_per_office'], 1, 2);
                    $data = [
                        'payrollSection' => $payrollSection,
                        'payrollFund' => $payrollFund,
                        // 'payrollUsers' => $payrollUsers,
                        // 'section' => $section,
                        'year' => $this->year,
                        'office' => $office,
                        'signatories' => $signatories,
                        'counter' => $counter,
                        'total_year_end_bonus_per_office' => $payrollSection['total_year_end_bonus_per_office'],
                        'total_cash_gift_per_office' => $payrollSection['total_cash_gift_per_office'],
                        'grand_total_year_end_bonus_per_office' => $grand_total_year_end_bonus_per_office,
                        'total_casab_loan_per_office' => $payrollSection['total_casab_loan_per_office'],
                        'net_amount' => $grand_total_year_end_bonus_per_office - $payrollSection['total_casab_loan_per_office'],
                        ];

                        $pdf = PDF::loadView('print-yeb-template', $data)->setOption(['dpi' => 80]);
                        $pdf->set_option("isPhpEnabled", true);
    
                    $employeesOfThisSection = [];
                    foreach($payrollFund->users(null, false, null, null, $office)->get() as $user){
                        // if($payrollFund->id == $user->fund_id){
                            array_push($employeesOfThisSection, $user);

                        // }
                    }
                }

                if(count($employeesOfThisSection) > 0){

                $filename = "";
        
                $others = 'other';
                if((count($employeesOfThisSection) - 1) > 2){
                    $others = 'others';
                }
    
                if(count($employeesOfThisSection) == 1){
                    $filename = $employeesOfThisSection[0]->full_name . ".pdf";
                }else if(count($employeesOfThisSection) > 1){
                    $filename = $employeesOfThisSection[0]->first_name[0] . ". " .$employeesOfThisSection[0]->last_name . " " . $employeesOfThisSection[0]->name_extn  . " & " . (count($employeesOfThisSection) - 1)  . " ". $others . ".pdf";
                }
    
                $trimmedFilename = preg_replace('/\s+/', ' ', $filename);

           
    
                $path = storage_path('app/yeb/' . $trimmedFilename);
                file_put_contents($path, $pdf->output());
                
                $oMerger->addPDF($path, 'all');
    

                // unset($employeesOfThisSection);


            }
                                                              
        }

        }

    //#toolbar=0
    try{
        $oMerger->merge();
        
        // Generate a unique filename for the merged PDF
        $filename = 'YEB_'. $this->year . '.pdf';
        // Save the merged PDF to storage/app/payrolls
        $path = storage_path('app/yeb/' . $filename);
        file_put_contents($path, $oMerger->output());
        }catch (\Exception $e) {
            // Handle the case where there are no PDFs to merge
            // For example, you can log an error message or perform other actions
            // You can access the exception message using $e->getMessage()
            // Example: Log::error('Failed to merge PDFs: ' . $e->getMessage());
        }

    $this->dispatchBrowserEvent('previewYebPdf', ['mypdf' => base64_encode($oMerger->output()), 'toolbar' => '']);
                                    
    }


    public function render()
    {
        // dd($this->processYearEndPayroll()[0]->sections['PIMO'][0]->signatories);
        $this->payrollFunds = $this->processYearEndPayroll();

        return view('livewire.yearend-bonus-component');
    }
}

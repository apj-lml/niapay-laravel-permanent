<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AgencySection;
use App\Models\User;
use App\Models\Fund;
use App\Models\UniformAllowance;
use App\Models\Signatory;

use Illuminate\Support\Facades\DB;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use PDF;

class UniformAllowanceComponent extends Component
{

    public $year, $mc, $uniformAllowance;

    protected $queryString = ['year', 'mc', 'uniformAllowance'];
    protected $listeners = [
        'refreshUniformAllowanceComponent' => '$refresh',
        'createPdf'
    ];

    public function mount()
    {
        $users = User::all('id', 'employee_id', 'last_name', 'first_name', 'middle_name', 'name_extn', 'position', 'sg_jg', 'step', 'daily_rate');
        foreach($users as $user){
            // Add or update the user

            $checkDupeUniformAllowance = UniformAllowance::where('year', $this->year)
            ->where('user_id', $user->id)
            ->get();

            if($checkDupeUniformAllowance->isNotEmpty() && $this->mc != ""){
                foreach ($checkDupeUniformAllowance as $user) {
                    $user->update([
                        'mc' => $this->mc,
                    ]);
                }
            }else{
                $user = UniformAllowance::create(
                    [
                        'mc' => $this->mc,
                        'year' => $this->year,
                        'name' => $user->full_name,
                        'position_title' => $user->position,
                        'sgjg' => $user->sg_jg,
                        'uniform_allowance' => $this->uniformAllowance,
                        'remarks' => null,
                        'user_id' => $user->id,
                    ]
                );
            }
        }
    }

    // public function processUniformAllowancePayroll($filterSection = null, $filterFund = null){

    //     DB::statement("SET SQL_MODE=''"); //this is the trick, use it just before your query to be able to GROUP
    //         $funds = Fund::with(['users' => function ($query) use ($filterSection) {

    //             if ($filterSection !== null) {
    //                 $query->whereHas('agencyUnit.agencySection', function ($subQuery) use ($filterSection) {
    //                     $subQuery->where('office', $filterSection);
    //                 });
    //             }
            
    //         }, 'users.agencyUnit', 'users.agencyUnit.agencySection', 'users.uniformAllowances'])
    //             ->has('users') // Ensure that the Fund has users
    //             ->get();
            
    //     if ($filterFund !== null && $filterFund != 0 ) {
    //         $funds = $funds->where('id', $filterFund);
    //     }

        
    //     if($funds->isEmpty()){
    //         return collect([]);
    //     }else{
    //         foreach($funds as $fund){
    //             $fund->sections = AgencySection::with(['users' => function ($query) use ($fund){
    //                 $query->where('fund_id', '=', $fund->id); 
    //                 $query->where('employment_status', '=', 'PERMANENT')->orWhere('employment_status', '=', 'COTERMINOUS'); 
    //                 $query->where('is_active', '=', 1); 
    //                 $query->where('include_to_payroll', '=', 1); 
    //             }])->select('*')->get()->groupBy('office');

    //         }


    //         $payrollFunds = $funds;
    //     }

    //     return $payrollFunds;
    // }

    public function processUniformAllowancePayroll($filterSection = null, $filterFund = null)
    {
        // Fetch Funds that have active users meeting the criteria
        $funds = Fund::whereHas('users', function ($query) use ($filterSection) {
            $query->where('is_active', 1)
                ->where('employment_status', 'PERMANENT')
                ->orWhere('employment_status', 'COTERMINOUS')
                ->where('include_to_payroll', 1);
    
            if ($filterSection !== null) {
                $query->whereHas('agencyUnit.agencySection', function ($subQuery) use ($filterSection) {
                    $subQuery->where('office', $filterSection);
                });
            }
        })
        ->with([
            'users' => function ($query) {
                $query->where('is_active', 1)
                    ->where('employment_status', 'PERMANENT')
                    ->orWhere('employment_status', 'COTERMINOUS')
                    ->where('include_to_payroll', 1);
            }
        ])
        ->get();
    
        // Apply fund filtering if needed
        if ($filterFund !== null && $filterFund != 0) {
            $funds = $funds->where('id', $filterFund);
        }
    
        if ($funds->isEmpty()) {
            return collect([]);
        }
    
        foreach ($funds as $fund) {
            // Fetch agency sections that have users within the same fund
            $fund->sections = AgencySection::whereHas('users', function ($query) use ($fund) {
                $query->where('fund_id', $fund->id)
                    ->where('employment_status', 'PERMANENT')
                    ->orWhere('employment_status', 'COTERMINOUS')
                    ->where('is_active', 1)
                    ->where('include_to_payroll', 1);
            })
            ->with(['users' => function ($query) use ($fund) {
                $query->where('fund_id', $fund->id)
                    ->where('employment_status', 'PERMANENT')
                    ->orWhere('employment_status', 'COTERMINOUS')
                    ->where('is_active', 1)
                    ->where('include_to_payroll', 1);
            }])
            ->get()
            ->groupBy('office'); // Group sections by office name
        }
    
        return $funds;
    }

    public function createPdf()
    {
        $payrollFunds = $this->processUniformAllowancePayroll();

        $oMerger = PDFMerger::init();

        foreach($payrollFunds as $payrollFund){
            $signatories = Signatory::where('docu', 'other_bonus')->get();

            foreach($payrollFund->sections as $office => $payrollSection){
                foreach($payrollSection as $section){
                    
                    // dd($payrollSection['total_year_end_bonus_per_office']);
                    $data = [
                        'payrollSection' => $payrollSection,
                        'payrollFund' => $payrollFund,
                        'year' => $this->year,
                        'office' => $office,
                        'signatories' => $signatories,
                        ];

                        $pdf = PDF::loadView('print-uniform-allowance-template', $data)->setOption(['dpi' => 60]);
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

                $path = storage_path('app/uniform_allowance/' . $trimmedFilename);
                file_put_contents($path, $pdf->output());
                
                $oMerger->addPDF($path, 'all');

            }
                                                              
        }

        }

    //#toolbar=0
    try{
        $oMerger->merge();
        
        // Generate a unique filename for the merged PDF
        $filename = 'UNIFORMALLOWANCE_'. $this->year . '.pdf';
        // Save the merged PDF to storage/app/payrolls
        $path = storage_path('app/uniform_allowance/' . $filename);
        file_put_contents($path, $oMerger->output());
        }catch (\Exception $e) {
            // Handle the case where there are no PDFs to merge
            // For example, you can log an error message or perform other actions
            // You can access the exception message using $e->getMessage()
            // Example: Log::error('Failed to merge PDFs: ' . $e->getMessage());
        }

    $this->dispatchBrowserEvent('previewUniformAllowancePdf', ['mypdf' => base64_encode($oMerger->output()), 'toolbar' => '']);
                                    
    }

    public function render()
    {
        $this->payrollFunds = $this->processUniformAllowancePayroll();

        return view('livewire.uniform-allowance-component');
    }
}

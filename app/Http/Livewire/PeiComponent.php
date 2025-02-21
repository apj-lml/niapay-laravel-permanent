<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AgencySection;
use App\Models\User;
use App\Models\Fund;
use App\Models\Pei;
use App\Models\Signatory;

use Illuminate\Support\Facades\DB;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use PDF;

class PeiComponent extends Component
{
    public $year, $mc, $pei;

    protected $queryString = ['year', 'mc', 'pei'];
    protected $listeners = [
        'refreshPeiComponent' => '$refresh',
        'createPdf'
    ];

    public function mount()
    {
        $users = User::all('id', 'employee_id', 'last_name', 'first_name', 'middle_name', 'name_extn', 'position', 'sg_jg', 'step', 'daily_rate');
        foreach($users as $user){
            // Add or update the user

            $checkDupePei = Pei::where('year', $this->year)
            ->where('user_id', $user->id)
            ->get();

            if($checkDupePei->isNotEmpty() && $this->mc != ""){
                foreach ($checkDupePei as $user) {
                    $user->update([
                        'mc' => $this->mc,
                    ]);
                }
            }else{
                $user = Pei::create(
                    [
                        'mc' => $this->mc,
                        'year' => $this->year,
                        'name' => $user->full_name,
                        'position_title' => $user->position,
                        'sgjg' => $user->sg_jg,
                        'pei' => $this->pei,
                        'remarks' => null,
                        'user_id' => $user->id,
                    ]
                );
            }
        }
    }

    public function processPeiPayroll($filterSection = null, $filterFund = null){

        DB::statement("SET SQL_MODE=''"); //this is the trick, use it just before your query to be able to GROUP
            $funds = Fund::with(['users' => function ($query) use ($filterSection) {

                if ($filterSection !== null) {
                    $query->whereHas('agencyUnit.agencySection', function ($subQuery) use ($filterSection) {
                        $subQuery->where('office', $filterSection);
                    });
                }
            
            }, 'users.agencyUnit', 'users.agencyUnit.agencySection', 'users.peis'])
                ->has('users') // Ensure that the Fund has users
                ->get();
            
        if ($filterFund !== null && $filterFund != 0 ) {
            $funds = $funds->where('id', $filterFund);
        }

        
        if($funds->isEmpty()){
            return collect([]);
        }else{
            foreach($funds as $fund){
                $fund->sections = AgencySection::with(['signatories', 'users' => function ($query) use ($fund){
                    $query->where('fund_id', '=', $fund->id); 
                    $query->where('employment_status', '=', 'PERMANENT')
                        ->orWhere('employment_status', '=', 'COTERMINOUS'); 
                    $query->where('is_active', '=', 1); 
                    $query->where('include_to_payroll', '=', 1); 
                }, 'users.peis' => function ($query){
                    $query->where('year', '=', $this->year); // Filter peis by year 2024
                }])->select('*')->get()->groupBy('office');

                $total_pei_per_office = 0.00;


                foreach ($fund->sections as $office => $sections) {
                
                    $total_pei_per_office += $fund->users(null, false, null, null, $office)->get()->sum(function ($user) {
                        return $user->peis->sum('pei');
                    });

                    $sections->put('total_pei_per_office', $total_pei_per_office);

                    $total_pei_per_office = 0.00;

                }
            }


            $payrollFunds = $funds;
        }

        return $payrollFunds;
    }


    public function createPdf()
    {
        $payrollFunds = $this->processPeiPayroll();

        $oMerger = PDFMerger::init();

        foreach($payrollFunds as $payrollFund){
            $signatories = Signatory::where('docu', 'other_bonus')->get();

            foreach($payrollFund->sections as $office => $payrollSection){
                foreach($payrollSection as $section){
                    
                    // dd($payrollSection['total_year_end_bonus_per_office']);
                    $data = [
                        'payrollSection' => $payrollSection,
                        'payrollFund' => $payrollFund,
                        // 'payrollUsers' => $payrollUsers,
                        // 'section' => $section,
                        'year' => $this->year,
                        'office' => $office,
                        'signatories' => $signatories,
                        'amount_due_per_office' => $payrollSection['total_pei_per_office'],
                        ];

                        $pdf = PDF::loadView('print-pei-template', $data)->setOption(['dpi' => 60]);
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

           
    
                $path = storage_path('app/pei/' . $trimmedFilename);
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
        $filename = 'PEI_'. $this->year . '.pdf';
        // Save the merged PDF to storage/app/payrolls
        $path = storage_path('app/pei/' . $filename);
        file_put_contents($path, $oMerger->output());
        }catch (\Exception $e) {
            // Handle the case where there are no PDFs to merge
            // For example, you can log an error message or perform other actions
            // You can access the exception message using $e->getMessage()
            // Example: Log::error('Failed to merge PDFs: ' . $e->getMessage());
        }

    $this->dispatchBrowserEvent('previewPeiPdf', ['mypdf' => base64_encode($oMerger->output()), 'toolbar' => '']);
                                    
    }

    public function render()
    {
        $this->payrollFunds = $this->processPeiPayroll();

        return view('livewire.pei-component');
    }
}

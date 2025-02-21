<?php

namespace App\Http\Livewire;

use App\Models\AgencyUnit;
use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AgencySection;
use App\Models\Allowance;
use App\Models\Fund;
use App\Models\Deduction;
use App\Models\PayrollIndex;
use App\Models\NewPayrollIndex;
use App\Models\indexDeduction;
use App\Models\NewPayrollIndexAllDed;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;
// use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Signatory;
use PDF;
use function PHPUnit\Framework\isEmpty;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Illuminate\Support\Facades\Storage;

class ProcessPayrollJobOrderComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $payrollEmploymentStatus,
            $payrollDateTo,
            $payrollDateFrom,
            $isLessFifteen,
            $additionalDeductions,
            $uniqueAdditionalDeductionGroups,
            $joDeductions,
            $joAllowances,
            $payrollFunds,
            $mypdf,
            $isEmptyFund,
            $overflow = 'auto';

    protected $listeners = ['openProcessPayrollJobOrder', 'createPDF', 'insertIndexDeductions', 'refreshProcessPayrollJobOrderComponent' => '$refresh'];

    protected $queryString = ['payrollEmploymentStatus', 'payrollDateFrom', 'payrollDateTo', 'isLessFifteen'];
    
    public function lockScroll()
    {
        if($this->overflow == 'auto'){
            $this->overflow = 'hidden';
        }else{
            $this->overflow = 'auto';
        }

        $this->dispatchBrowserEvent('srollLock', ['overflow' => $this->overflow]);
    }

    public function showEmployeeProfile($userId)
    {
        $this->emit('openEmployeeProfileModal', $userId);
    }


    public function updateIsActive($ids=[], $payrollUserSectionId=null)
    {
        $is_active = 1;

        if($ids){
            foreach($ids as $eachId){
                $data = User::findOrFail($eachId);        
                if($data->include_to_payroll == 1){
                    $is_active = 0;
                }
                $data->update([
                    'include_to_payroll' => $is_active,
                ]);
            }
        }else{
            $agencySectionUsers = AgencySection::where('id', $payrollUserSectionId)->with('users')->get();

            foreach($agencySectionUsers as $sectionUsers){
                foreach($sectionUsers->users as $sectionUser){
                    if($sectionUser->include_to_payroll == 1){
                        $is_active = 0;
                    }
                    $sectionUser->update([
                        'include_to_payroll' => $is_active,
                    ]);
                }
            }
        }
    }

    public function createPDF($FilterSection = null, $FilterFund = null) {
        if($FilterSection == 0){
            $FilterSection = null;
        }
        $this->payrollFunds = $this->processPayroll($FilterSection, $FilterFund, true);
        // $this->payrollFunds = $this->processPayroll(null, null, true);


        $this->isEmptyFund = $this->payrollFunds->isEmpty();
        if($this->payrollFunds->isNotEmpty()){
        //------------------ PREVIEWING PDF-----------------
            $oMerger = PDFMerger::init();

            foreach ($this->payrollFunds as $thisFund) {
                
                $signatoryArr = array();
                $itemNoArr = array();
                foreach($thisFund->sections as $office => $userSections){
                    foreach($userSections as $userSection){
                    $usersForSig = $thisFund->users;

                    $agencySectionIds = $usersForSig->map(function($user) use ($office) {
                        return $user->agencyUnit()
                                    ->with(['agencySection' => function($query) use ($office) {
                                        $query->where('office', $office);
                                    }])
                                    ->first()
                                    ->agencySection
                                    ->id ?? null;
                    })->filter()->unique()->values()->toArray();

                  
                    $mysignatories = Signatory::whereIn('agency_section_id', $agencySectionIds)
                    ->where('docu','wages')
                    ->whereNotIn('type', ['Box A [Preparer]', 'Box D [Approver]', 'Box E [Certified]', 'Box C [Finance Unit Head]'])
                    ->get();

                    $mysignatoriesStatic = Signatory::whereIn('type', ['Box A [Preparer]', 'Box C [Finance Unit Head]', 'Box D [Approver]', 'Box E [Certified]'])
                        ->where('docu','wages')
                        ->groupBy('type')
                        ->get();



                    $allSignatories = $mysignatories->merge($mysignatoriesStatic);

           
                    if(isset($userSection->total_basic_pay)){

                        $data = [
                            'payrollEmploymentStatus' => $this->payrollEmploymentStatus,
                            'payrollFund' => $thisFund,
                            'payrollUserSection' => $userSection,
                            'joDeductions'=>$this->joDeductions,
                            'joAllowances'=>$this->joAllowances,
                            'additionalDeductions'=>$this->additionalDeductions,
                            'uniqueAdditionalDeductionGroups'=>$this->uniqueAdditionalDeductionGroups,
                            'payrollDateFrom'=>Carbon::parse($this->payrollDateFrom),
                            'payrollDateTo'=>Carbon::parse($this->payrollDateTo),
                            'isLessFifteen' => $this->isLessFifteen,
                            'office' => $office,
                            'signatories' => $allSignatories,
                        ];
                        
                        // dd($data);


                        $pdf = PDF::loadView('print-payroll-template-jo', $data)->setOption(['dpi' => 118,]);
                 
                        $employeesOfThisSection = [];
                        foreach($thisFund->users as $user){
                            if($user->agencyUnit->agencySection->office == $userSection->office){
                                array_push($employeesOfThisSection, $user);
                            }
                        }



                        //THIS IS TO SORT THE RESULT. HOWEVER IT IS ALREADY SORTED SO NO NEED TO DO THIS
                        // $employeesOfThisSection = $thisFund->users->filter(function ($user) use ($userSection) {
                        //     return $user->agencyUnit->agencySection->id == $userSection->id;
                        // })->sortBy(function ($user) {
                        //     return $user->last_name . $user->first_name . $user->middle_name;
                        // });
                        
                        $filename = "";

                        $others = 'other';
                        if((count($employeesOfThisSection) - 1) > 2){
                            $others = 'others';
                        }

                        if(count($employeesOfThisSection) == 1){

                            $filename = $this->payrollDateFrom . ' to ' . $this->payrollDateTo . "_" . $employeesOfThisSection[0]->full_name . ".pdf";
                        }else if(count($employeesOfThisSection) > 1){
                            $filename = $this->payrollDateFrom . ' to ' . $this->payrollDateTo . "_" . $employeesOfThisSection[0]->first_name[0] . ". " .$employeesOfThisSection[0]->last_name . " " . $employeesOfThisSection[0]->name_extn  . " & " . (count($employeesOfThisSection) - 1)  . " ". $others . ".pdf";
                        }

                        $trimmedFilename = preg_replace('/\s+/', ' ', $filename);
                
                        $pdf->set_option("isPhpEnabled", true);
                        $path = storage_path('app/payrolls/' . $trimmedFilename);
                        file_put_contents($path, $pdf->output());
                        
                        $oMerger->addPDF($path, 'all');

                        unset($employeesOfThisSection);

                    }
                    // break;


                }

                $signatoryArr = array();
                $itemNoArr = array();

                }

            }

                    // Merge all the PDFs
                    try{
                        $oMerger->merge();
                        
                        // Generate a unique filename for the merged PDF
                        $filename = $thisFund->fund_description .'_'. $this->payrollDateFrom . ' to ' . $this->payrollDateTo . '.pdf';
                        // Save the merged PDF to storage/app/payrolls
                        $path = storage_path('app/payrolls/' . $filename);
                        file_put_contents($path, $oMerger->output());
                        }catch (\Exception $e) {
                            // Handle the case where there are no PDFs to merge
                            // For example, you can log an error message or perform other actions
                            // You can access the exception message using $e->getMessage()
                            // Example: Log::error('Failed to merge PDFs: ' . $e->getMessage());
                        }
                                                          //#toolbar=0
        $this->dispatchBrowserEvent('testPdf', ['mypdf' => base64_encode($oMerger->output()), 'toolbar' => '']);
        $this->insertIndexDeductions();
        // $this->generatePayslip();

       }
      }

    public function insertIndexDeductions(){

        foreach($this->payrollFunds as $thisFund){

  
            foreach($thisFund->sections as $myFund){
                // dd($myFund);
            }

            if($thisFund->users){

                foreach($thisFund->users->where('basic_pay', '>', 0) as $user){
                    $datefrom = Carbon::parse($this->payrollDateFrom);
                    $dateto = Carbon::parse($this->payrollDateTo);
    
                    $monthName = $datefrom->format('F');
                    $dayFrom = $datefrom->format('d');
                    $dayTo = $dateto->format('d');
                    $final_date = $monthName . ' ' . $dayFrom . '-' . $dayTo;

                    $userattendance = $user->attendances->filter(function ($attendance) use ($datefrom, $dateto) {
                        $startDate = Carbon::parse($attendance->start_date);
                        $endDate = Carbon::parse($attendance->end_date);
                        return $startDate->eq($datefrom) && $endDate->eq($dateto);
                        //return $startDate->eq($datefrom); //eq means it is equal
                    });

                        if($userattendance->isNotEmpty()){ 

                            $flattenedUserAttendance = Arr::flatten($userattendance);

                            $checkPayrollIndexDupe = NewPayrollIndex::where('user_id', $user->id)
                                            ->where('period_covered_from', $datefrom)
                                            ->where('period_covered_to', $dateto)
                                            ->get();


                            if($checkPayrollIndexDupe->isEmpty()){

                                $newPayrollIndexLastInsertedId = DB::table('new_payroll_index')->insertGetId([
                                    'name' => $user->full_name,
                                    'office' => $user->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['office'],
                                    'office_section' => $user->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['section_description'] . " / " . $user->agencyUnit()->with('agencySection')->first()->unit_description,
                                    'imo' => "PANGASINAN IMO",
                                    'position_title' => $user->position,
                                    'sg_jg' => $user->sg_jg,
                                    'daily_monthly_rate' => $user->daily_rate,
                                    'employment_status' => $user->employment_status,
                                    'period_covered' => $final_date,
                                    'period_covered_from' => $datefrom,
                                    'period_covered_to' => $dateto,
                                    'funding_charges' => $thisFund->fund_description,
                                    'tin' => $user->tin,
                                    'phic_no' => $user->phic_no,
                                    'hdmf' => $user->hdmf,
                                    'first_half' => $flattenedUserAttendance[0]['first_half'],
                                    'second_half' => $flattenedUserAttendance[0]['second_half'],
                                    'days_rendered' => $flattenedUserAttendance[0]['days_rendered'],
                                    'first_half_basic_pay' => $user->first_half,
                                    'second_half_basic_pay' => $user->second_half,
                                    'basic_pay' => $user->basic_pay,
                                    'filename' => '',
                                    'created_at' => now(),
                                    'user_id' => $user->id,
                                ]);

                                if(isset($user->user_deductions)){
                                    $userDeductions = $user->user_deductions->filter(function ($mydeduction) {
                                        foreach($mydeduction as $ded){
                                            if($ded->pivot){
                                                error_log($ded->pivot);
                                                if($ded->pivot->active_status == 1){
                                                    return $ded;
                                                }

                                            }
                                        }

                                    });

                                foreach($userDeductions as $user_deductions){
                                    foreach($user_deductions as $user_deduction){
                                            $checkDedDupe = NewPayrollIndexAllDed::where('new_payroll_index_id', $newPayrollIndexLastInsertedId)
                                            ->where('npiad_amount', $user_deduction->pivot->amount)
                                            ->where('npiad_description', $user_deduction->description)
                                            ->where('npiad_group', $user_deduction->deduction_group)
                                            ->whereRelation('newPayrollIndex', 'period_covered_from', $datefrom)
                                            ->whereRelation('newPayrollIndex', 'period_covered_to', $dateto)
                                            ->get();

                                            if($checkDedDupe->isEmpty()){
                                                $npiadLastInsertedIdDed = DB::table('new_payroll_index_all_deds')->insertGetId([
                                                    'npiad_type' => "DEDUCTION",
                                                    'npiad_amount' => $user_deduction->pivot->amount,
                                                    'npiad_group' => $user_deduction->deduction_group,
                                                    'npiad_description' => $user_deduction->description,
                                                    'npiad_for' => $user_deduction->deduction_for,
                                                    'npiad_sort_position' => $user_deduction->sort_position,
                                                    'new_payroll_index_id' => $newPayrollIndexLastInsertedId,
                                                    'created_at' => now()
                                                ]);

                                                            }

                           
                                                                            }
                                                                        }
                                }
                                
                                    if(isset($user->user_allowances)){

                                        $userAllowances = $user->user_allowances->filter(function ($allow) {
                                            // dd($allow[0]['status']);
                                            return $allow[0]['status'] === 'ACTIVE'; // Assuming 'status' field indicates active or not
                                        });

                                        foreach($userAllowances as $user_allowances){
                                            foreach($user_allowances as $user_allowance){
                                                $checkAllDupe = NewPayrollIndexAllDed::where('npiad_amount', $user_allowance->pivot->amount)
                                                    ->where('npiad_description', $user_allowance->description)
                                                    ->where('npiad_group', $user_allowance->deduction_group)
                                                    ->whereRelation('newPayrollIndex', 'period_covered_from', $datefrom)
                                                    ->whereRelation('newPayrollIndex', 'period_covered_to', $dateto)
                                                    ->get();
            
                                                if($checkAllDupe->isEmpty()){
                                                    $npiadLastInsertedIdAll = DB::table('new_payroll_index_all_deds')->insertGetId([
                                                        'npiad_type' => "ALLOWANCE",
                                                        'npiad_amount' => $user_allowance->pivot->amount,
                                                        'npiad_group' => $user_allowance->allowance_group,
                                                        'npiad_description' => $user_allowance->description,
                                                        'npiad_for' => $user_allowance->allowance_for,
                                                        'npiad_sort_position' => $user_allowance->sort_position,
                                                        'new_payroll_index_id' => $newPayrollIndexLastInsertedId,
                                                        'created_at' => now()
                                                    ]);
                                            }
            
                                            }
                                        }
                                            }
    
                            }else{
                                error_log("DUPLICATE NA ENTRIES SA INDEXING");
                                foreach ($checkPayrollIndexDupe as $payrollIndex) {
                                    $payrollIndex->update([
                                        'name' => $user->full_name,
                                        'office' => $user->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['office'],
                                        'office_section' => $user->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['section_description'] . " / " . $user->agencyUnit()->with('agencySection')->first()->unit_description,
                                        // 'office_section' => $user->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['section_description'],
                                        'imo' => "PANGASINAN IMO",
                                        'position_title' => $user->position,
                                        'sg_jg' => $user->sg_jg,
                                        'daily_monthly_rate' => $user->daily_rate,
                                        'employment_status' => $user->employment_status,
                                        'period_covered' => $final_date,
                                        'first_half' => $flattenedUserAttendance[0]['first_half'],
                                        'second_half' => $flattenedUserAttendance[0]['second_half'],
                                        'days_rendered' => $flattenedUserAttendance[0]['days_rendered'],
                                        'first_half_basic_pay' => $user->first_half,
                                        'second_half_basic_pay' => $user->second_half,
                                        'basic_pay' => $user->basic_pay,
                                        'period_covered_from' => $datefrom,
                                        'period_covered_to' => $dateto,
                                        'funding_charges' => $thisFund->fund_description,
                                        'tin' => $user->tin,
                                        'phic_no' => $user->phic_no,
                                        'hdmf' => $user->hdmf,
                                        'created_at' => now(),
                                        'user_id' => $user->id,
                                    ]);

                                    if(isset($user->user_deductions)){
                                        foreach($user->user_deductions as $user_deductions){
                                            foreach($user_deductions as $user_deduction){
                                                    $checkDedDupe = NewPayrollIndexAllDed::
                                                    // where('npiad_amount', $user_deduction->pivot->amount)
                                                    where('new_payroll_index_id', $payrollIndex->id)
                                                    ->where('npiad_description', $user_deduction->description)
                                                    ->whereRelation('newPayrollIndex', 'period_covered_from', $datefrom)
                                                    ->whereRelation('newPayrollIndex', 'period_covered_to', $dateto)
                                                    ->get();

                                                    if($checkDedDupe->isEmpty()){
                                                        $npiadLastInsertedIdDed = DB::table('new_payroll_index_all_deds')->insertGetId([
                                                            'npiad_type' => "DEDUCTION",
                                                            'npiad_amount' => $user_deduction->pivot->amount,
                                                            'npiad_group' => $user_deduction->deduction_group,
                                                            'npiad_description' => $user_deduction->description,
                                                            'npiad_for' => $user_deduction->deduction_for,
                                                            'npiad_sort_position' => $user_deduction->sort_position,
                                                            'new_payroll_index_id' => $payrollIndex->id,
                                                            'updated_at' => now()
                                                        ]);
                                                    } else {
                                                       // Update each item in the collection

                                                        foreach ($checkDedDupe as $deduction) {
                                                            $deduction->update([
                                                                'npiad_amount' => $user_deduction->pivot->amount,
                                                                'updated_at' => now()
                                                            ]);
                                                        }
                                                    }
    
                                            }
                                        }
                                    }

                                    if(isset($user->user_allowances)){
                                        foreach($user->user_allowances as $user_allowances){
                                            foreach($user_allowances as $user_allowance){
                                                    $checkDedDupe = NewPayrollIndexAllDed::
                                                    // where('npiad_amount', $user_allowance->pivot->amount)
                                                    where('new_payroll_index_id', $payrollIndex->id)
                                                    ->where('npiad_description', $user_allowance->description)
                                                    ->whereRelation('newPayrollIndex', 'period_covered_from', $datefrom)
                                                    ->whereRelation('newPayrollIndex', 'period_covered_to', $dateto)
                                                    ->get();

                                                    if($checkDedDupe->isEmpty()){
                                                        $npiadLastInsertedIdDed = DB::table('new_payroll_index_all_deds')->insertGetId([
                                                            'npiad_type' => "ALLOWANCE",
                                                            'npiad_amount' => $user_allowance->pivot->amount,
                                                            'npiad_group' => $user_allowance->allowance_group,
                                                            'npiad_description' => $user_allowance->description,
                                                            'npiad_for' => $user_allowance->allowance_for,
                                                            'npiad_sort_position' => $user_allowance->sort_position,
                                                            'new_payroll_index_id' => $payrollIndex->id,
                                                            'updated_at' => now()
                                                        ]);
                                                    } else {
                                                       // Update each item in the collection

                                                        foreach ($checkDedDupe as $allowance) {
                                                            $allowance->update([
                                                                'npiad_amount' => $user_allowance->pivot->amount,
                                                                'updated_at' => now()
                                                            ]);
                                                        }
                                                    }
    
                                            }
                                        }
                                    }



                                }



                              
                            }
                    }
    
                }


            }


        }


    }


    public function searchDeduction($searchDeductionType, $yourArray)
    {
        // Initialize an empty array to store the results
        $results = [];

        // Iterate through the original array
        foreach ($yourArray as $category => $items) {
            foreach ($items as $item) {
                if (isset($item['deduction_type']) && $item['deduction_type'] === $searchDeductionType) {
                    $results[] = $item;
                }
            }
        }

        return $results;
    }

    public function showAddIndividualAttendanceModal($userId, $attendanceId = null)
    {
        $daysRendered = 0;

        if($attendanceId == null){
            $from = Carbon::parse($this->payrollDateFrom);
            $to = Carbon::parse($this->payrollDateTo);

        }else{
            $getdaysRendered = Attendance::findOrFail($attendanceId);
            $daysRendered = $getdaysRendered->days_rendered;
        }

        $this->emit('openAddIndividualAttendanceModal', number_format( (float) $daysRendered, 3, '.', ''), $this->payrollDateFrom, $this->payrollDateTo, $userId, $attendanceId);
    }


    public function processPayroll($filterSection = null, $filterFund = null, $filterAttendance = false){

        $to = date($this->payrollDateTo);
        $from = date($this->payrollDateFrom);
        $tempBasicPay = 0;
        $inputIsLessFifteen = 0;


        if($this->isLessFifteen != 'full_month'){
            $inputIsLessFifteen = 1;
            
        }else{
            $inputIsLessFifteen = 0;

        }

        DB::statement("SET SQL_MODE=''"); //this is the trick, use it just before your query to be able to GROUP
            $funds = Fund::with(['users' => function ($query) use ($filterSection, $filterAttendance, $from, $to, $inputIsLessFifteen) {

                if($this->isLessFifteen == 'full_month'){
                    $query->where('include_to_payroll', 1)
                    ->where('is_active', 1)
                    ->orderBy('last_name', 'asc');
                }else{

                    $query->where('include_to_payroll', 1)
                    ->where('is_active', 1)
                    ->where('is_less_fifteen', $inputIsLessFifteen)
                    ->orderBy('last_name', 'asc');
                }

                if ($filterSection !== null) {
                    // dd($filterSection);
                    $query->whereHas('agencyUnit.agencySection', function ($subQuery) use ($filterSection) {
                        $subQuery->where('office', $filterSection);
                    });
                }
            
                if ($filterAttendance) {
                    $query->whereHas('attendances', function ($subQuery) use ($from, $to) {
                        if ($from !== null) {
                            $subQuery->where('start_date', '=', $from);
                        }
            
                        if ($to !== null) {
                            $subQuery->where('end_date', '=', $to);
                        }
                    });
                }
            }, 'users.agencyUnit', 'users.agencyUnit.agencySection', 'users.attendances'])
                ->has('users') // Ensure that the Fund has users
                ->get();

            
        if ($filterFund !== null && $filterFund != 0 ) {
            $funds = $funds->where('id', $filterFund);
        }

        //SELECTION OF DEDUCTION AND ALLOWANCES
        if($this->payrollEmploymentStatus == "Job Order" || $this->payrollEmploymentStatus == "Contract of Service"){
            // $this->joDeductions = Deduction::where('deduction_group', '<>', 'GSIS')->where('status', 'ACTIVE')->get();
            $this->joDeductions = Deduction::where('deduction_group', '<>', 'GSIS')->get();
        }else{
            $this->joAllowances = Allowance::all();

            // if($this->isLessFifteen != 'less_fifteen_second_half'){
                $this->joDeductions = Deduction::all();
            // }else{
            //     $this->joDeductions = collect([]);
            // }
        }

        // if($this->isLessFifteen != 'less_fifteen_second_half'){
            $this->additionalDeductions = Deduction::where('deduction_group', 'OTHER')->get();

            $this->uniqueAdditionalDeductionGroups = $this->additionalDeductions->unique('deduction_group');
        // }else{
        //     $this->additionalDeductions = collect([]);
        //     $this->uniqueAdditionalDeductionGroups = collect([]);
        // }

        
        if($funds->isEmpty()){
            return collect([]);
        }else{

            foreach($funds as $fund){
                    $fund->sections = AgencySection::whereHas('unit.user', function ($query) {
                        $query->where('employment_status', '=', 'PERMANENT')
                            ->orWhere('employment_status', '=', 'COTERMINOUS');
                    })->select('*')->get()->groupBy('office');

                $JOUsers = $fund->users->where('employment_status', 'PERMANENT')->where('employment_status', 'COTERMINOUS');

                foreach($JOUsers as $key => $JOUser){
                        $JOUser->total_user_deduction = 0;
                        $JOUser->total_user_allowance = 0;

                        $days_rendered = 0.00;
                        $days_rendered_first_half = 0.00;
                        $days_rendered_second_half = 0.00;

                        $attendances = $JOUser->attendances->where('start_date', '=', $from)->where('end_date', '=', $to)->isNotEmpty();
                        // GET USER ATTENDANCE TO CALCULATE BASIC PAY
                        if($attendances){
                            $JOUser->basic_pay = bcdiv((float)((float)$JOUser->daily_rate) * ((float)$JOUser->attendances()->where('start_date', '=', $from)->where('end_date', '=', $to)->first()->days_rendered), 1, 2);
                            $days_rendered = ((float)$JOUser->attendances()->where('start_date', '=', $from)->where('end_date', '=', $to)->first()->days_rendered);
                            $days_rendered_first_half = ((float)$JOUser->attendances()->where('start_date', '=', $from)->where('end_date', '=', $to)->first()->first_half);
                            $days_rendered_second_half = ((float)$JOUser->attendances()->where('start_date', '=', $from)->where('end_date', '=', $to)->first()->second_half);


                            // Setting User Allowances
                            if(!$JOUser->employeeAllowances->isEmpty()){
                                $employeeAllowances = $JOUser->employeeAllowances()->wherePivot('active_status', 1)->get();
                                $employeeAllowances = collect($employeeAllowances)->sortBy('sort_position')->groupBy('allowance_group');
                                $JOUser->user_allowances = $employeeAllowances;

                                $user_allowance_per_allowance = $JOUser->employeeAllowances()->wherePivot('active_status', 1)->get();
                                $user_allowance_per_allowance = collect($user_allowance_per_allowance)->sortBy('sort_position')->groupBy('allowance_group');
                                $JOUser->user_allowance_per_allowance = $user_allowance_per_allowance;
                                // $JOUser->user_allowance_per_allowance = $JOUser->employeeAllowances->where('active_status', 'ACTIVE')->sortBy('sort_position')->groupBy('allowance_type');?
        
                            if(isset($JOUser->user_allowances)){
                                foreach($JOUser->user_allowances as $allowances_user){
                                    foreach($allowances_user as $allowance){
                                        $JOUser->total_user_allowance += $allowance->pivot->amount;
                                        }
                                    }
                                }
                            }


                        if(!$JOUser->employeeDeductions->isEmpty() && $this->isLessFifteen != 'less_fifteen_second_half'){                                                  //->where('status', 'ACTIVE')
                            $employeeDeductions = $JOUser->employeeDeductions()->wherePivot('active_status', 1)->get();
                            $employeeDeductions = collect($employeeDeductions)->sortBy('sort_position')->groupBy('deduction_group');
                            $JOUser->user_deductions = $employeeDeductions;
                                                                                                            //->where('status', 'ACTIVE', 'or')
                            $user_deductions_per_deduction = $JOUser->employeeDeductions()->wherePivot('active_status', 1)->get();
                            $user_deductions_per_deduction = collect($user_deductions_per_deduction)->sortBy('sort_position')->groupBy('deduction_type');
                            $JOUser->user_deductions_per_deduction = $user_deductions_per_deduction;
                            
                            if(isset($JOUser->user_deductions) && $this->isLessFifteen != 'less_fifteen_second_half'){
                                foreach($JOUser->user_deductions as $deduction_users){

                                    //OLD CODE
                                    foreach($deduction_users as $deduction){
                                        $JOUser->total_user_deduction += $deduction->pivot->amount;
                                    }

                                    // NEW CODE
                                        // $JOUser->total_user_deduction += $deduction_users->pivot->amount;
                                }
                            }

                        }

                        $JOUser->first_half = round(((($JOUser->basic_pay + $JOUser->total_user_allowance) - $JOUser->total_user_deduction) / $days_rendered) * $days_rendered_first_half, 2);
                        $JOUser->second_half = round(((($JOUser->basic_pay + $JOUser->total_user_allowance) - $JOUser->total_user_deduction) / $days_rendered) * $days_rendered_second_half, 2);

                        }else{
                            $JOUser->basic_pay = 0.00;
                            $JOUser->first_half = 0.00;
                            $JOUser->second_half = 0.00;
                            $JOUser->total_user_deduction = 0.00;
                            $JOUser->total_user_allowance = 0.00;
                        }
                
                        foreach($fund->sections as $section){
                            if(isset($section[0]->id)){
                                if($section[0]->office == $JOUser->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['office']){

                                    // if($fund->id == $JOUser->fund_id){
                                        // Total Basic Pay | Total Deduction | Total Net Pay
                                        if($attendances){
                                        $section[0]->total_basic_pay += $JOUser->basic_pay;
                                        $section[0]->total_deduction += $JOUser->total_user_deduction;
                                        $section[0]->total_allowance += $JOUser->total_user_allowance;
                                        $section[0]->total_first_half += $JOUser->first_half;
                                        $section[0]->total_second_half += $JOUser->second_half;
                                        $section[0]->total_net_pay += (((float)$JOUser->basic_pay + (float)$JOUser->total_user_allowance) - $JOUser->total_user_deduction);
                                    }

                   
                                            // END
                                        
                                    if(isset($JOUser->user_deductions_per_deduction)){
                                        $mykeys = $JOUser->user_deductions_per_deduction->keys();
                                        foreach($mykeys as $mykey){
                                            foreach($JOUser->user_deductions_per_deduction[$mykey] as $user_deduction){
                                                if(isset($section[0]->grand_total_deduction[$mykey])){
            
                                                    $temp = array();
                                                    $temp = $section[0]->grand_total_deduction[$mykey];
                                                    $section[0]->grand_total_deduction[$mykey] = ['total'=>($temp["total"] + $user_deduction->pivot->amount),
                                                                                            'sort_position'=>$user_deduction->sort_position,
                                                                                            'deduction_group'=>$user_deduction->deduction_group];
            
                                                }else{
                                                    if(isset($section[0]->grand_total_deduction)){
                                                        $section[0]->grand_total_deduction->put($mykey, ['total'=>$user_deduction->pivot->amount, 
                                                                                                    'sort_position'=>$user_deduction->sort_position, 
                                                                                                    'deduction_group'=>$user_deduction->deduction_group]);
            
                                                    }else{
                                                        $section[0]->grand_total_deduction = collect([$mykey => ['total'=>$user_deduction->pivot->amount, 
                                                                                                            'sort_position'=>$user_deduction->sort_position, 
                                                                                                            'deduction_group'=>$user_deduction->deduction_group]]);
                                                    }
            
                                                }
            
                                            }
                                        }
            
                                    }


                                    if(isset($JOUser->user_allowance_per_allowance)){
                                        $mykeys = $JOUser->user_allowance_per_allowance->keys();
                                        foreach($mykeys as $mykey){
                                            foreach($JOUser->user_allowance_per_allowance[$mykey] as $user_allowance){
                                                if(isset($section[0]->grand_total_allowance[$mykey])){
            
                                                    $temp = array();
                                                    $temp = $section[0]->grand_total_allowance[$mykey];
                                                    $section[0]->grand_total_allowance[$mykey] = ['total'=>($temp["total"] + $user_allowance->pivot->amount),
                                                                                            'sort_position'=>$user_allowance->sort_position,
                                                                                            'allowance_group'=>$user_allowance->allowance_group];
            
                                                }else{
                                                    if(isset($section[0]->grand_total_allowance)){
                                                        $section[0]->grand_total_allowance->put($mykey, ['total'=>$user_allowance->pivot->amount, 
                                                                                                    'sort_position'=>$user_allowance->sort_position, 
                                                                                                    'allowance_group'=>$user_allowance->allowance_group]);
            
                                                    }else{
                                                        $section[0]->grand_total_allowance = collect([$mykey => ['total'=>$user_allowance->pivot->amount, 
                                                                                                            'sort_position'=>$user_allowance->sort_position, 
                                                                                                            'allowance_group'=>$user_allowance->allowance_group]]);
                                                    }
            
                                                }
            
                                            }
                                        }
            
                                    }

                                    // }// THIS IS MY TEST IF

                                    }

                                }



                                }
                
                }//END OF JOUSER
            }
            
            $payrollFunds = $funds;
        return $payrollFunds;
        }
    }


    public function render()
    {
        $this->payrollFunds = $this->processPayroll();

        $datefrom = Carbon::parse($this->payrollDateFrom);
        $dateto = Carbon::parse($this->payrollDateTo);

        $monthName = $datefrom->format('F');
        $dayFrom = $datefrom->format('d');
        $dayTo = $dateto->format('d');
        $yearTo = $dateto->format('Y');
        $payrollPeriodTxt = $monthName . ' ' . $dayFrom . '-' . $dayTo .', ' .$yearTo;
                    
        return view('livewire.process-payroll-job-order-component',[
                'payrollPeriodTxt' => $payrollPeriodTxt
            ]);
    }
}

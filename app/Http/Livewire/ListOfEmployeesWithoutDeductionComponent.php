<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\NewPayrollIndex;
use App\Models\Deduction;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;


class ListOfEmployeesWithoutDeductionComponent extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $payrollDateFrom, $payrollDateTo, $npiadDescription = 'HDMF PREMIUM';

    public function searchEmployeesWithoutDeduction()
    {

    }

    public function render()
    {
        // $listOfUsers = User::
        // ->whereDoesntHave('employeeDeductions')
        // ->orderby('last_name')
        // ->orderby('first_name')
        // ->paginate(20);

        $listOfUsers = NewPayrollIndex::where('employment_status', 'CASUAL')
        ->where('period_covered_from', $this->payrollDateFrom)
        ->where('period_covered_to', $this->payrollDateTo)
        ->whereDoesntHave('newPayrollIndexAllDed', function ($query) {
            // Add your conditions for NewPayrollIndexAllDed model here
            $query->where('npiad_description', $this->npiadDescription); // Example condition
        })
        ->paginate(12);

        // ->whereDoesntHave('newPayrollIndexAllDed')
        // ->orderby('name')
        // ->get();

        $listOfDeductions = Deduction::all();

        // dd($listOfUsers);

        return view('livewire.list-of-employees-without-deduction-component', ['listOfUsers' => $listOfUsers, 'listOfDeductions' => $listOfDeductions]);
    }
}

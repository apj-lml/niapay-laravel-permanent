<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\DB;

class ListOfEmployees extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $is_active,
            $employee_id,
            $name,
            $email,
            $password,
            $section,
            $unit,
            $position,
            $employment_status,
            $sg_jg,
            $step,
            $daily_rate,
            $monthly_rate,
            $fund,
            $include_to_payroll,
            $searchVal = "",
            $tab = 'active'; // track which tab is active;;
 
    public function editUser($id)
    {
        $data = User::findOrFail($id);
    }

    public function updatingSearchVal()
    {
        $this->resetPage();
    }

    public function setTab($tab)
    {
        $this->tab = $tab;

        // If you are updating search/filter results
        $this->updatingSearchVal();

        // Reset pagination if needed
        $this->resetPage();
    }

    public function updateIsActive($id)
    {
        $is_active = 1;
        $data = User::findOrFail($id);

        if($data->include_to_payroll == 1){
            $is_active = 0;
        }

        $data->update([
            'include_to_payroll' => $is_active,
        ]);

    }

    public function showEmployeeProfile($userId)
    {
        $this->emit('openEmployeeProfileModal', $userId);
    }

    public function showIndexModal($userId)
    {
        $this->emit('openIndexModal', $userId);
    }



    public function render()
    {
        // $users = User::where('employment_status', 'PERMANENT')
        // ->orWhere('employment_status', 'COTERMINOUS')
        // // ->where(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name)"), 'like', "%{$this->searchVal}%")
        // ->where("last_name", 'like', "%{$this->searchVal}%")
        // ->orderby('last_name')
        // ->orderby('first_name')
        // ->paginate(20);

        $query = User::whereIn('employment_status', ['PERMANENT', 'COTERMINOUS'])->where(function ($query) {
            $query->where('first_name', 'like', "%{$this->searchVal}%")
                ->orWhere('middle_name', 'like', "%{$this->searchVal}%")
                ->orWhere('last_name', 'like', "%{$this->searchVal}%");
        })->orderBy('last_name')
        ->orderBy('first_name');

         // Filter depending on tab
         if ($this->tab === 'active') {
            $query->where('is_active', 1);
        } else {
            $query->where('is_active', 0);
        }

        $activeCount = User::where('employment_status', ['PERMANENT', 'COTERMINOUS'])->where('is_active', 1)->count();
        $inactiveCount = User::where('employment_status', ['PERMANENT', 'COTERMINOUS'])->where('is_active', 0)->count();


        $users = $query->paginate(10);
        

        return view('livewire.list-of-employees',[
            'users' => $users,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ]);
    }

}

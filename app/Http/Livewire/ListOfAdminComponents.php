<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;


class ListOfAdminComponents extends Component
{
    public $adminUsers;

    public function mount()
    {
        $this->adminUsers = User::where('role', 1)->get();
    }

    public function showEmployeeProfile($userId)
    {
        $this->emit('openEmployeeProfileModal', $userId);
        
    }
    
    public function render()
    {
        return view('livewire.list-of-admin-components');
    }
}

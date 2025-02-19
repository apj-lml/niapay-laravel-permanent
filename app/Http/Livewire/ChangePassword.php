<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Auth;
class ChangePassword extends Component
{

    public $password;
    public $newPassword;
    public $newPassword_confirmation;
    public $fieldType = 'password';
 
    protected $rules = [
        'newPassword' => 'required|confirmed|min:6',
    ];

    public function submit()
    {
        $this->validate();
        if (Hash::check($this->password,  Auth::user()->password)) {
            Auth::user()->update([
                'password' => Hash::make($this->newPassword),
            ]);
            session()->flash('message', 'Password successfully updated.');
            $this->resetErrorBag();
            $this->reset();
        }else{
            $this->addError('password', 'Your password does not match your current password.');
        }
        Auth::user()->first_name;
    }

    public function changeType()
    {
        $password = $this->password;
        $newPassword = $this->newPassword;
        $newPassword_confirmation = $this->newPassword_confirmation;
        if($this->fieldType == 'password'){
            $this->fieldType = 'text';
        }else{
            $this->fieldType = 'password';
        }
        $this->password = $password;
        $this->newPassword = $newPassword;
        $this->newPassword_confirmation = $newPassword_confirmation;
        // dd($this->fieldType);
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}

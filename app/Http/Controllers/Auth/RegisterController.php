<?php

namespace App\Http\Controllers\Auth;

use App\Models\AgencyUnit;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\DeductionUser;
use App\Models\Fund;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

     public function showRegistrationForm()
     {
         $agencyUnits = AgencyUnit::all();
         $listOfFunds = Fund::all();
 
         return view('auth.register', compact('agencyUnits', 'listOfFunds'));
     }

     
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'employee_id' => ['required', 'string', 'min:6', 'max:6', 'unique:users'],
            'password' => ['confirmed'],
            // 'section' => ['required'],
            'unit' => ['required'],
            'position' => ['required'],
            'employment_status' => ['required'],
            'sg_jg' => ['required'],
            // 'step' => ['required'],
            'daily_rate' => ['decimal:2', 'nullable'],
            'monthly_rate' => ['decimal:2', 'nullable'],
            // 'fund_id' => ['required'],
            'tin' => ['required'],
            'phic_no' => ['required'],
            'hdmf' => ['required'],
            'gsis' => ['required'],
            'is_less_fifteen' => ['required'],
        ]);
    }

    protected function create(Request $request)
    {
        $data = $request->all();
        // dd($data);

        // Check if the password is not null
        if ($data['password'] != null) {
            // Hash the password
            $data['password'] = Hash::make($data['password']);
        }

        // $data['tin'] = 'N/A';
        // $data['phic_no'] = 'N/A';
        // $data['gsis'] = 'N/A';
        // $data['hdmf'] = 'N/A';
        // $data['sss'] = 'N/A';
        // $data['sss'] = 'N/A';
        // dd($data);

        // Create a new user with the agency unit relationship
        $user = new User([
            ...$data,
        ]);

        // Save the user to the database
        $user->save();

        // DeductionUser::create([
        //     'user_id' => $user->id,
        //     'deduction_id' => $data['ded_phic_id'],
        //     'amount' => $data['ded_phic'],
        //     'frequency' => 1,
        //     'active_status' => 1
        // ]);

        // DeductionUser::create([
        //     'user_id' => $user->id,
        //     'deduction_id' => $data['ded_pagibig_id'],
        //     'amount' => $data['ded_pagibig'],
        //     'frequency' => 1,
        //     'active_status' => 1
        // ]);

        // DeductionUser::create([
        //     'user_id' => $user->id,
        //     'deduction_id' => $data['ded_wht_id'],
        //     'amount' => $data['ded_wht'],
        //     'frequency' => 1,
        //     'active_status' => 1
        // ]);

        // DeductionUser::create([
        //     'user_id' => $user->id,
        //     'deduction_id' => $data['ded_sss_id'],
        //     'amount' => $data['ded_sss'],
        //     'frequency' => 1,
        //     'active_status' => 1
        // ]);

        // DeductionUser::create([
        //     'user_id' => $user->id,
        //     'deduction_id' => $data['ded_disallow_id'],
        //     'amount' => $data['ded_disallow'],
        //     'frequency' => 1,
        //     'active_status' => 1
        // ]);
        
        // return $user;
        return redirect('register')->with('status', 'User registered!');
    
}
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    // protected function create(array $data)
    // public function register(Request $data)
    // {
    //     $this->validator($data->all())->validate();

    //     // event(new Registered($user = $this->create($data->all())));

    //     $user = User::create([
    //         'name' => $data['name'],
    //         'employee_id' => $data['employee_id'],
    //         'password' => Hash::make($data['password']),
    //         // 'section' => $data['section'],
    //         'agency_unit_id' => $data['agency_unit_id'],
    //         'position' => $data['position'],
    //         'employment_status' => $data['employment_status'],
    //         'sg_jg' => $data['sg_jg'],
    //         'step' => $data['step'],
    //         'daily_rate' => $data['daily_rate'],
    //         'monthly_rate' => $data['monthly_rate'],
    //         'fund_id' => $data['fund_id'],
    //         'tin' => $data['tin'],
    //         'phic_no' => $data['phic_no'],
    //         'hdmf' => $data['hdmf'],
    //         'gsis' => $data['gsis'],

    //     ]);

    //     $this->guard()->login($user);
 
    //     if ($data = $this->registered($data, $user)) {
    //         return $data;
    //     }
 
    //     return $data->wantsJson()
    //                 ? new Response('', 201)
    //                 : redirect($this->redirectPath());

    //     // return User::create([
    //     //     'name' => $data['name'],
    //     //     'employee_id' => $data['employee_id'],
    //     //     'password' => Hash::make($data['password']),
    //     //     // 'section' => $data['section'],
    //     //     'agency_unit_id' => $data['agency_unit_id'],
    //     //     'position' => $data['position'],
    //     //     'employment_status' => $data['employment_status'],
    //     //     'sg_jg' => $data['sg_jg'],
    //     //     'step' => $data['step'],
    //     //     'daily_rate' => $data['daily_rate'],
    //     //     'monthly_rate' => $data['monthly_rate'],
    //     //     'fund_id' => $data['fund_id'],
    //     //     'tin' => $data['tin'],
    //     //     'phic_no' => $data['phic_no'],
    //     //     'hdmf' => $data['hdmf'],
    //     //     'gsis' => $data['gsis'],

    //     // ]);


    // }

    // public function register(Request $request)
    // {
    //     $this->validator($request->all())->validate();
    //     dd($request->all());
    //     event(new Registered($user = $this->create($request->all())));
 
    //     return redirect()->route('/');
    // }
}

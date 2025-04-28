<?php
    
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('without-edit', function () {
    return view('index - Complete without edit');
});

Route::get('print-payroll-template-jo', function () {
    return view('print-payroll-template-jo');
});
// Auth::routes();

Route::get('process-payroll-jo', function () {
    return view('process-payroll-jo');
})->name('process-payroll-jo');

Route::get('/processed-payrolls', function () {
    return view('processed-payrolls');
})->name('processed-payrolls');

Route::get('/payslip/{userId}', function () {
    return view('payslip');
})->name('payslip');

Route::get('/process-payslip', function () {
    return view('process-payslip');
})->name('process-payslip');

Auth::routes();


Auth::routes(['register' => false]);

Route::get('/custom-register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/custom-register', 'App\Http\Controllers\Auth\RegisterController@create');


Route::get('/showpayslip/{filename}', 'App\Http\Controllers\PayslipController@show')->name('showpayslip');

Route::get('/openProcessedPayroll/{filename}', 'App\Http\Controllers\ProcessedPayrollController@openProcessedPayroll')->name('openProcessedPayroll');


Route::get('/payroll-finder', function () {
    return view('payroll-finder');
})->name('payroll-finder');

Route::get('/year-end-bonus-landing-page', function () {
    return view('year-end-bonus-landing-page');
})->name('year-end-bonus-landing-page');

Route::get('/year-end-bonus', function () {
    return view('year-end-bonus');
})->name('year-end-bonus');


Route::get('/cna-landing-page', function () {
    return view('cna-landing-page');
})->name('cna-landing-page');

Route::get('/cna', function () {
    return view('cna');
})->name('cna');


Route::get('/pei-landing-page', function () {
    return view('pei-landing-page');
})->name('pei-landing-page');

Route::get('/pei', function () {
    return view('pei');
})->name('pei');

Route::get('/ua-landing-page', function () {
    return view('ua-landing-page');
})->name('ua-landing-page');

Route::get('/uniform-allowance', function () {
    return view('uniform-allowance');
})->name('uniform-allowance');


Route::get('/process-payroll', function () {
    return view('process-payroll');
})->name('process-payroll');


// payroll list download
Route::get('/payrolls/download/{filename}', function ($filename) {
    $path = storage_path("app/payrolls/$filename");
    if (!file_exists($path)) abort(404);
    return response()->download($path);
})->name('payroll.download');

// Route::get('payroll-clerk', ['middleware' => 'auth', function() {
//     Auth::routes();
// }]);


// Route::group(['middleware' => 'web'], function () {

//     // Registration Routes...
//     Route::get('admin-register', 'Admin\RegisterController@showRegistrationForm')->name('admin.register');
//     Route::post('admin-register', 'Admin\RegisterController@register');

//     // Moving here will ensure that sessions, csrf, etc. are included in all these routes
//     // Route::group(['prefix'=>'admin',  'middleware' => 'admin'], function(){

//         // Route::get('/', function(){
//         //     return view('admin.index');
//         // });

//         // Route::get('/user', function(){
//         //     return view('admin.user');
//         // });


//     // });

// });

Route::get('/payroll', [App\Http\Controllers\HomeController::class, 'index'])->name('payroll');

Route::get('/deduction-summary', function () {
    return view('deduction-summary');
})->name('/deduction-summary');

Route::get('/system-settings', function () {
    return view('system-settings');
})->name('/system-settings');

Route::get('/change-password', function () {
    return view('change-password-base');
})->name('/change-password');

Route::get('/list-of-admin', function () {
    return view('list-of-admin');
})->name('/list-of-admin');

Route::get('/manage-units-sections', function () {
    return view('manage-units-sections');
})->name('/manage-units-sections');

Route::get('/manage-funds', function () {
    return view('manage-funds');
})->name('/manage-funds');

Route::get('/manage-signatories', function () {
    return view('manage-signatories');
})->name('/manage-signatories');

Route::get('/manage-allowances-deductions', function () {
    return view('manage-allowances-deductions');
})->name('/manage-allowances-deductions');

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewPayrollIndex extends Model
{
    use HasFactory;

    protected $table = 'new_payroll_index';

    protected $dates = [
        'period_covered_to',
        'period_covered_from',
        // other date attributes
    ];

    protected $fillable = [
        'id',
        'name',
        'last_name',
        'first_name',
        'middle_name',
        'name_extn',
        'birthdate',
        'office',
        'office_section',
        'imo',
        'position_title',
        'sg_jg',
        'daily_monthly_rate',
        'employment_status',
        'period_covered',
        'period_covered_from',
        'period_covered_to',
        'gsis',
        'gsis_crn',
        'tin',
        'phic_no',
        'hdmf',
        'days_rendered',
        'first_half_basic_pay',
        'second_half_basic_pay',
        'basic_pay',
        'funding_charges',
        'fund_acct_no',
        'atm_no',
        'is_less_fifteen',
        'filename',
            ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function newPayrollIndexAllDed()
    {
        return $this->hasMany(NewPayrollIndexAllDed::class);
    }
}

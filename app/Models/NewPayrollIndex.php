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
        'tin',
        'phic_no',
        'hdmf',
        'days_rendered',
        'basic_pay',
        // 'pera',
        // 'medical',
        // 'meal',
        // 'children',
        // 'total_allowances',
        // 'gross_amount',
        // 'tax',
        // 'gsis_premium',
        // 'gsis_consoloan',
        // 'gsis_salary_loan',
        // 'gsis_cash_adv',
        // 'gsis_emergency',
        // 'gsis_gfal',
        // 'gsis_mpl',
        // 'gsis_cpl',
        // 'hdmf_premium',
        // 'hdmf_mp2',
        // 'hdmf_mploan',
        // 'hdmf_cal',
        // 'phic',
        // 'COOP',
        // 'cna',
        // 'total_deductions',
        // 'incentives_benefits',
        // 'net_pay',
        'funding_charges',
        'filename',
        // 'dv_payroll_no',
        // 'remarks',
        // 'user_id',
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

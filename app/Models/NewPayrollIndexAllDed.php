<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewPayrollIndexAllDed extends Model
{
    use HasFactory;

    protected $table = 'new_payroll_index_all_deds';

    protected $fillable = [
        'id',
        'npiad_type',
        'npiad_deduction_id',
        'npiad_amount',
        'npiad_application_no',
        'npiad_loan_granted',
        'npiad_start_term',
        'npiad_end_term',
        'npiad_group',
        'npiad_description',
        'npiad_for',
        'npiad_sort_position',
        'new_payroll_index_id'
    ];

    public function newPayrollIndex()
    {
        return $this->belongsTo(newPayrollIndex::class);
    }

}

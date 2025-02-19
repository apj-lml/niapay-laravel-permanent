<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class indexDeduction extends Model
{
    use HasFactory;

    protected $table = 'index_deductions';

    protected $fillable = [
        'description',
        'deduction_type',
        'deduction_group',
        'amount',
        'sort_position',
        'payroll_index_id',
            ];

    public function payrollIndex()
        {
            return $this->belongsTo(PayrollIndex::class, 'payroll_index_id');
        }


    public static function getDeductionTypesByYear($year, $deductionGroup = null, $excludedTypes = [])
        {
            $query = self::whereHas('payrollIndex', function ($query) use ($year) {
                $query->whereYear('period_covered_from', $year);
            });
        
            if ($deductionGroup) {
                $query->where('deduction_group', $deductionGroup);
            }
        
            if (!empty($excludedTypes)) {
                $query->whereNotIn('deduction_type', $excludedTypes);
            }
        
            return $query->pluck('deduction_type')
                ->unique()
                ->values();
        }

    public static function getDeductionByPayrollPeriod($year, $payrollPeriodFrom, $payrollPeriodTo, $deductionGroup = null)
        {
            $query = self::whereHas('payrollIndex', function ($query) use ($year, $payrollPeriodFrom, $payrollPeriodTo) {
                $query->whereYear('period_covered_from', $year)
                      ->whereBetween('period_covered_from', [$payrollPeriodFrom, $payrollPeriodTo])
                      ->whereBetween('period_covered_to', [$payrollPeriodFrom, $payrollPeriodTo]);
            });
        
            if ($deductionGroup) {
                $query->where('deduction_group', $deductionGroup);
            }
        
            return $query->orderBy('sort_position')
                ->get();
        }
}

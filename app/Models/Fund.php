<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    protected $fillable = [
        'fund_description',
        'fund_obligation_description',
        'fund_uacs_code',
        'basic_pay'
    ];

    // public function users()
    // {
    //     return $this->hasMany(User::class)->where('include_to_payroll', 1)
    //                 ->orderBy('last_name', 'asc');;
    // }

    // public function users($filterSection = null)
    // {
    //     $query = $this->hasMany(User::class)->where('include_to_payroll', 1)
    //                 ->orderBy('last_name', 'asc');

    //     if ($filterSection !== null) {
    //         $query->whereHas('agencyUnit.agencySection', function ($subQuery) use ($filterSection) {
    //             $subQuery->where('id', $filterSection);
    //         });
    //     }

    //     return $query;
    // }


    public function users($filterSection = null, $filterAttendance = false, $dateFrom = null, $dateTo = null, $filterOffice = null)
    {
        $query = $this->hasMany(User::class)->where('include_to_payroll', 1)
                    ->orderBy('last_name', 'asc');
    
        if ($filterSection !== null) {
            $query->whereHas('agencyUnit.agencySection', function ($subQuery) use ($filterSection) {
                $subQuery->where('id', $filterSection);
            });
        }

        if ($filterOffice !== null) {
            $query->whereHas('agencyUnit.agencySection', function ($subQuery) use ($filterOffice) {
                $subQuery->where('office', $filterOffice);
            });
        }
    
        if ($filterAttendance) {
            $query->whereHas('attendances', function ($subQuery) use ($dateFrom, $dateTo) {
                if ($dateFrom !== null) {
                    $subQuery->where('start_date', '>=', $dateFrom);
                }
    
                if ($dateTo !== null) {
                    $subQuery->where('end_date', '<=', $dateTo);
                }
            });
        }
    
        return $query;
    }

}

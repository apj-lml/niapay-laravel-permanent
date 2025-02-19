<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearendBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'mc',
        'year',
        'name',
        'emp_id',
        'position_title',
        'daily_rate',
        'monthly_rate',
        'year_end_bonus',
        'cash_gift',
        'total_year_end_bonus',
        'casab_loan',
        'net_amount',
        'user_id',
    ];
}

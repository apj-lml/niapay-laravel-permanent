<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MidyearBonus extends Model
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
        'mid_year_bonus',
        'total_mid_year_bonus',
        'casab_loan',
        'net_amount',
        'user_id',
    ];
}

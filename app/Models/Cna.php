<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cna extends Model
{
    use HasFactory;

    protected $table = 'cna';

    protected $fillable = [
        'mc',
        'year',
        'name',
        'emp_id',
        'position_title',
        'cna',
        'no_mos',
        'amount_due',
        'remarks',
        'user_id',
    ];
}

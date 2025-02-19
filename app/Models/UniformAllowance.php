<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniformAllowance extends Model
{
    use HasFactory;
    
    protected $table = 'uniform_allowances';

    protected $fillable = [
        'mc',
        'year',
        'name',
        'position_title',
        'sgjg',
        'uniform_allowance',
        'remarks',
        'user_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pei extends Model
{
    use HasFactory;

    protected $table = 'peis';

    protected $fillable = [
        'mc',
        'year',
        'name',
        'position_title',
        'sgjg',
        'pei',
        'remarks',
        'user_id',
    ];
}

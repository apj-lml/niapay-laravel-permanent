<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    protected $table = 'deductions';
    protected $fillable = [
        'description',
        'account_title',
        'deduction_type',
        'deduction_group',
        'deduction_for',
        'uacs_code_lfps',
        'uacs_code_cob',
        'status',
        'sort_position',

    ];


    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}

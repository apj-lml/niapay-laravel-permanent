<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollIndex extends Model
{
    use HasFactory;

    protected $table = 'payroll_indices';

    public function indexDeductions()
    {
        return $this->hasMany(indexDeduction::class, 'payroll_index_id');
    }

}

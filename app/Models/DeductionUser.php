<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionUser extends Model
{
    use HasFactory;

    protected $table = 'deduction_user';

    protected $casts = [
        'end_term' => 'date',
        'start_term' => 'date',
        'loan_granted' => 'decimal:2',
    ];

    protected $fillable = [
        'user_id',
        'deduction_id',
        'amount',
        'application_no',
        'loan_granted',
        'start_term',
        'end_term',
        'frequency',
        'active_status',
        'remarks',
            ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

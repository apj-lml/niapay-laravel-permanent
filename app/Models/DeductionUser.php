<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionUser extends Model
{
    use HasFactory;

    protected $table = 'deduction_user';

    protected $fillable = [
        'user_id',
        'deduction_id',
        'amount',
        'frequency',
        'active_status',
        'remarks',
            ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

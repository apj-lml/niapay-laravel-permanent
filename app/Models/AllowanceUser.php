<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowanceUser extends Model
{
    use HasFactory;

    protected $table = 'allowance_user';

    protected $fillable = [
        'user_id',
        'allowance_id',
        'amount',
        'frequency',
        'active_status'
            ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

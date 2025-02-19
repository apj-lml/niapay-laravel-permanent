<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    
    protected $table = 'attendances';

    // protected $casts = [
    //     'start_date' => 'date',
    //     'end_date' => 'date',
    // ];
    
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'days_rendered',
        'first_half',
        'second_half'
            ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}


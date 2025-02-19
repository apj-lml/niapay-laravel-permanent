<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyUnit extends Model
{
    use HasFactory;

    protected $table = 'agency_units';

    protected $fillable = [
        'unit_code',
        'unit_description',
        'unit_section',
        'agency_section_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function agencySection()
    {
        return $this->belongsTo(AgencySection::class);
    }
}

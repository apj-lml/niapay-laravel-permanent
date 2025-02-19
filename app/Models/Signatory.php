<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'agency_section_id',
        'type',
        'office',
        'docu'
    ];

    public function agencySection()
    {
        return $this->belongsTo(AgencySection::class);
    }

}

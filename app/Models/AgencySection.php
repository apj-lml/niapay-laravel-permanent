<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencySection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_code',
        'section_description',
        'office'
    ];

    public function unit()
    {
        return $this->hasOne(AgencyUnit::class);
        // return $this->belongsTo(AgencyUnit::class, 'agency_unit_id'); // Adjust the foreign key if necessary
    }

    public function user(){
        return $this->hasOneThrough(User::class, AgencyUnit::class);
    }

    public function users(){
        return $this->hasManyThrough(User::class, AgencyUnit::class);
    }

    public function signatories(){
        return $this->hasMany(Signatory::class);
    }

    // public function signatories($filterDocumentType = null)
    // {
    //     $query = $this->hasMany(Signatory::class);

    //     if ($filterDocumentType !== null) {
    //         $query->whereHas('agencySection', function ($subQuery) use ($filterOffice) {
    //             $subQuery->where('docu', $filterDocumentType);
    //         });
    //     }

    //     return $query;
    // }
}

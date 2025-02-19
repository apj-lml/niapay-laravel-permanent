<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'is_active',
        'employee_id',
        // 'name',
        'last_name',
        'first_name',
        'middle_name',
        'name_extn',
        'email',
        'password',
        // 'section',
        'agency_unit_id',
        'position',
        'employment_status',
        'sg_jg',
        'step',
        'daily_rate',
        'monthly_rate',
        'fund_id',
        'tin',
        'phic_no',
        'hdmf',
        // 'sss',
        'gsis',
        'role',
        'include_to_payroll',
        'basic_pay',
        'total_user_deduction',
        'user_deductions',
        'user_deductions_per_deduction',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['full_name'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function yebs()
    {
        return $this->hasMany(YearendBonus::class);
    }

    public function cnas()
    {
        return $this->hasMany(Cna::class);
    }

    public function peis()
    {
        return $this->hasMany(Pei::class);
    }

    public function uniformAllowances()
    {
        return $this->hasMany(UniformAllowance::class);
    }

    public function filteredAttendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function newPayrollIndex()
    {
        return $this->hasMany(NewPayrollIndex::class);
    }

    public function employeeAllowances()
    {
        return $this->belongsToMany(Allowance::class)
        ->withPivot('id', 'amount', 'frequency', 'active_status')
        ->withTimestamps();
    }

    public function employeeDeductions()
    {
        return $this->belongsToMany(Deduction::class)
        ->withPivot('id', 'amount', 'frequency', 'active_status', 'remarks')
        ->withTimestamps();
    }

    public function agencyUnit()
    {
        return $this->belongsTo(AgencyUnit::class);
    }

    public function fund(){
        return $this->belongsTo(Fund::class);
    }
    

    public function scopeJobOrder($query)
    {
        return $query->where('employment_status', '=', 'CASUAL');
    }

    public function getFullNameAttribute() // notice that the attribute name is in CamelCase.
    {
        $fullName = $this->last_name . ', ' . $this->first_name . ' ' . $this->name_extn .' '. $this->middle_name;
        return trim(preg_replace('/\s+/', ' ', $fullName));
    }

    public function setDailyRateAttribute($value)
    {
        $this->attributes['daily_rate'] = str_replace(',', '', $value);
    }

}

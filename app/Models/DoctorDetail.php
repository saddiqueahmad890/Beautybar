<?php

namespace App\Models;
use App\Models\UserLogs;
use App\services\UserLogServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class DoctorDetail extends Model
{
    use  Loggable;
    protected $fillable = [
        'hospital_department_id',
        'user_id',
        'specialist',
        'designation',
        'biography',
        'commission'
    ];

    public function hospitalDepartment()
    {
        return $this->belongsTo(HospitalDepartment::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCompanyIdAttribute()
    {
        return $this->user->company_id;
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}

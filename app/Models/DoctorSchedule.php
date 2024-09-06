<?php

namespace App\Models;
use App\Models\UserLogs;
use App\services\UserLogServices;
use App\Traits\Loggable;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use Loggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'weekday',
        'start_time',
        'end_time',
        'avg_appointment_duration',
        'serial_type',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

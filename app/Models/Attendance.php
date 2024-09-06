<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'checked_in',
        'checked_out',
        'created_by',
        'updated_by',
        'status'
    ];

    protected $dates = ['checked_in', 'checked_out'];

    // Define the relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Calculate total duty time for the day
    public static function calculateDutyTimeForDay($userId, $date)
    {
        // Fetch all attendance records for the user on the specified date
        $attendances = self::where('user_id', $userId)
            ->whereDate('checked_in', $date)
            ->whereNotNull('checked_in')
            ->whereNotNull('checked_out')
            ->orderBy('checked_in')
            ->get();

        $totalDutyTime = 0;
        $dutyTimes = [];

        if ($attendances->isEmpty()) {
            return [
                'totalDutyTime' => $totalDutyTime, // in minutes
                'dutyTimes' => $dutyTimes // individual duty times
            ];
        }

        $firstCheckIn = $attendances->first()->checked_in;
        $lastCheckOut = $attendances->last()->checked_out;

        foreach ($attendances as $attendance) {
            $dutyTime = $attendance->checked_out->diffInMinutes($attendance->checked_in);
            $totalDutyTime += $dutyTime;
            $dutyTimes[] = [
                'checked_in' => $attendance->checked_in,
                'checked_out' => $attendance->checked_out,
                'dutyTime' => $dutyTime // in minutes
            ];
        }

        return [
            'totalDutyTime' => $totalDutyTime, // in minutes
            'firstCheckInToLastCheckOut' => $lastCheckOut->diffInMinutes($firstCheckIn), // in minutes
            'dutyTimes' => $dutyTimes // individual duty times
        ];
    }


}

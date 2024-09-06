<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->format('Y-m-d')); // Default to today if no date is provided

        $previousDate = (new Carbon($date))->subDay()->format('Y-m-d');
        $nextDate = (new Carbon($date))->addDay()->format('Y-m-d');
        $today = now()->format('Y-m-d');

        $user = auth()->id(); // Get the ID of the currently authenticated user

        // Debugging: Output the user ID
        logger()->info("User ID: $user");

        // Fetch all doctors
        $doctorsQuery = User::role('Doctor')->where('status', '1');

        // Apply filters if any
        $doctors = User::role('Doctor')->where('status', '1')->select(['id', 'name']);

        // Apply filters
        if ($request->has('name') && !empty($request->name)) {
            $doctors = $doctors->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('start_date') && $request->date) {
            $startDate = Carbon::parse($request->date)->startOfDay();
            $doctors = $doctors->whereHas('attendances', function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            });
        }

        $doctors = $doctors->with('attendances')->get(); // Eager load attendances

        if ($user == 1) {
            $attendances = Attendance::select('user_id', 'status', DB::raw('DATE(created_at) as date'), DB::raw('MIN(created_at) as first_check_in'), DB::raw('MAX(updated_at) as last_check_out'))
                ->whereDate('created_at', $date)
                ->groupBy('user_id', 'status', 'date')
                ->latest()
                ->get();
        } else {
            $attendances = Attendance::select('user_id', DB::raw('DATE(created_at) as date'), DB::raw('MIN(created_at) as first_check_in'), DB::raw('MAX(updated_at) as last_check_out'))
                ->whereDate('created_at', $date)
                ->where('user_id', $user)
                ->groupBy('user_id', 'status', 'date')
                ->get();

            $doctors = User::role('Doctor')->where('status', '1')->where('id', $user)->get();
        }

        // Ensure each attendance has the user loaded
        foreach ($attendances as $attendance) {
            $attendance->load('user'); // Eager load user for each attendance

            $first_check_in = new Carbon($attendance->first_check_in);
            $last_check_out = new Carbon($attendance->last_check_out);

            $diff = $first_check_in->diff($last_check_out);
            $hours = $diff->h;
            $minutes = $diff->i;

            $attendance->total_duty_time = sprintf('%02d:%02d', $hours, $minutes);
        }

        $doctorsWithAttendance = $doctors->map(function ($doctor) use ($attendances) {
            $attendance = $attendances->firstWhere('user_id', $doctor->id);

            return [
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name, // Get doctor's name
                'attendance' => $attendance,
            ];
        });
        $isStartDate = $today;

        // Existing code...

        return view('attendance.index', compact('doctorsWithAttendance', 'previousDate', 'nextDate', 'today', 'isStartDate'));
    }

    public function export(Request $request)
    {
        $date = $request->query('date', now()->format('Y-m-d')); // Default to today if no date is provided

        // Fetch attendances based on the date
        $attendances = Attendance::select('user_id', DB::raw('DATE(created_at) as date'), DB::raw('MIN(created_at) as first_check_in'), DB::raw('MAX(updated_at) as last_check_out'))
            ->whereDate('created_at', $date)
            ->groupBy('user_id', 'date')
            ->get();

        foreach ($attendances as $attendance) {
            $first_check_in = new Carbon($attendance->first_check_in);
            $last_check_out = new Carbon($attendance->last_check_out);
            $diff = $first_check_in->diff($last_check_out);
            $hours = $diff->h;
            $minutes = $diff->i;
            $attendance->total_duty_time = sprintf('%02d:%02d', $hours, $minutes);
        }

        // Format data for export
        $data = $attendances->map(function ($attendance) {
            return [
                'user_id' => $attendance->user_id, // Only user_id, not related model data
                'date' => $attendance->date,
                'first_check_in' => $attendance->first_check_in,
                'last_check_out' => $attendance->last_check_out,
                'total_duty_time' => $attendance->total_duty_time,
            ];
        })->toArray();

        $headers = ['User ID', 'Date', 'First Check In', 'Last Check Out', 'Total Duty Time'];

        return Excel::download(new GenericExport($data, $headers), 'attendance.xlsx');
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();

        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereNotNull('checked_in')
            ->whereNull('checked_out')
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Already checked in today.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'checked_in' => now(),
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'status' => 1
        ]);

        return redirect()->back()->with('success', 'Checked in successfully.');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();

        $latestAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereNotNull('checked_in')
            ->whereNull('checked_out')
            ->latest()
            ->first();

        if ($latestAttendance) {
            $latestAttendance->update([
                'checked_out' => now(),
                'updated_by' => $user->id,
                'status' => 0
            ]);

            return redirect()->back()->with('success', 'Checked out successfully.');
        }

        return redirect()->back()->with('error', 'No check-in record found or already checked out.');
    }

    public function adminCheckIn(Request $request, User $doctor)
    {
        $existingAttendance = Attendance::where('user_id', $doctor->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereNotNull('checked_in')
            ->whereNull('checked_out')
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Already checked in today.');
        }

        Attendance::create([
            'user_id' => $doctor->id,
            'checked_in' => now(),
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'status' => 1 // Set status to 1 after checking in
        ]);

        return redirect()->back()->with('success', 'Checked in successfully.');
    }

    public function adminCheckOut(Request $request, User $doctor)
    {
        $latestAttendance = Attendance::where('user_id', $doctor->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereNotNull('checked_in')
            ->whereNull('checked_out')
            ->latest()
            ->first();

        if ($latestAttendance) {
            $latestAttendance->update([
                'checked_out' => now(),
                'updated_by' => Auth::user()->id,
                'status' => 0 // Reset status to 0 after checking out
            ]);

            return redirect()->back()->with('success', 'Checked out successfully.');
        }

        return redirect()->back()->with('error', 'No check-in record found or already checked out.');
    }
}

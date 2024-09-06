<?php

namespace App\Http\Controllers;

use App\Mail\NewAppointment;
use App\Mail\NewUserCredential;
use App\Models\DoctorSchedule;
use App\Models\PatientAppointment;
use App\Models\User;
use App\Models\UserLogs;
use App\Models\DoctorDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Models\AppointmentStatus;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Mail\DoctorAppointmentReminder;
use App\Mail\AppointmentReminder;


class PatientAppointmentController extends Controller
{
    /**
     * Constructor

     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:patient-appointment-read|patient-appointment-create|patient-appointment-update|patient-appointment-delete', ['only' => ['index', 'show', 'getAppointmentDoctorWise']]);
        $this->middleware('permission:patient-appointment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:patient-appointment-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:patient-appointment-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $currentDate = now()->format('Y-m-d');
        $tomorrowDate = now()->addDay()->format('Y-m-d');
        $appointments = PatientAppointment::whereBetween('appointment_date', [$currentDate, $tomorrowDate])
            ->where(function ($query) use ($currentDate) {
                $query->whereNull('last_email_sent_at')
                      ->orWhere('last_email_sent_at', '<', $currentDate);
            })->get();
        foreach ($appointments as $appointment) {
            //Mail::to($appointment->user->email)->send(new AppointmentReminder($appointment));
            //Mail::to($appointment->doctor->email)->send(new DoctorAppointmentReminder($appointment));
            $appointment->last_email_sent_at = now()->format('Y-m-d');
            $appointment->save();
        }


        $patientAppointments = $this->filter($request)->orderBy('id', 'desc')->paginate(10);



        if (auth()->user()->hasRole('Doctor'))
            $doctors = User::role('Doctor')->where('id', auth()->id())->where('status', '1')->get(['id', 'name']);
        else
            $doctors = User::role('Doctor')->where('status', '1')->get(['id', 'name']);

        if (auth()->user()->hasRole('Patient'))
            $patients = User::role('Patient')->where('id', auth()->id())->where('status', '1')->get(['id', 'name']);
        else
            $patients = User::role('Patient')->where('status', '1')->get(['id', 'name']);

        return view('patient-appointment.index', compact('patientAppointments', 'patients', 'doctors'));
    }

    /**
     * Filter function
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function filter(Request $request)
    {
        $query = PatientAppointment::query();

        if (auth()->user()->hasRole('Doctor')) {
            $query->where('doctor_id', auth()->id());
        } elseif ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if (auth()->user()->hasRole('Patient')) {
            $query->where('user_id', auth()->id());
        } elseif ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->appointment_date) {
            $query->where('appointment_date', $request->appointment_date);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('appointment_date', [$request->start_date, $request->end_date]);
        }

        return $query;
    }


    /**
     * Get doctorwise appointment slots
     *
     * @param Request
     * @return \Illuminate\Http\Response
     */
    public function getScheduleDoctorWise(Request $request)
    {
        if ($request->lang)
            app()->setLocale($request->lang);

        $request->validate([
            'userId' => ['required', 'integer', 'exists:users,id'],
            'appointmentDate' => ['required', 'date']
        ]);
        $weekday = date('l', strtotime($request->appointmentDate));
        $doctor = User::role('Doctor')->where('id', $request->userId)->where('status', '1')->first();
        $schedule = [];
        if ($doctor)
            $schedule = $doctor->doctorSchedules()->where('weekday', $weekday)->where('status', '1')->first();

        $slots = '<option value="">' . __('Appointment Not Found') . '</option>';
        if ($schedule)
            $slots = $this->makeSlots($request, $schedule);
        return response()->json($slots, 200);
    }


    public function createFromPatientDetails($userid)
    {
        $doctors = User::role('Doctor')->where('status', '1')->get(['id', 'name']);
        $patients = User::role('Patient')->where('status', '1')->with('patientDetails')->get(['id', 'name']);
        $selectedPatientId = $userid;

        return view('patient-appointment.create', compact('doctors', 'patients', 'selectedPatientId'));
    }

    public function create()
    {
        $doctors = User::role('Doctor')->where('status', '1')->get(['id', 'name']);
        $patients = User::role('Patient')->where('status', '1')->with('patientDetails')->get(['id', 'name']);

        return view('patient-appointment.create', compact('doctors', 'patients'));
    }


    public function store(Request $request)
    {
        $this->validation($request);

        $data = $request->only(['user_id', 'doctor_id', 'appointment_date', 'problem']);
        $data['company_id'] = session('company_id');
        $slots = explode('-', $request->appointment_slot);
        $data['start_time'] = $slots[1];
        $data['end_time'] = $slots[2];
        $data['sequence'] = $slots[0];

        $appointment = PatientAppointment::create($data);

        $appointment['appointment_number'] = getDocNumber($appointment->id, 'APT');

        $appointment->save();


        Mail::to($appointment->user->email)
            ->queue(new NewAppointment($appointment));

        return redirect()->route('patient-appointments.index', $appointment->id)->with('success', trans('Patient Appointment Created Successfully'));
    }

    /**
     * Performs appointment booking
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bookAppointment(Request $request)
    {
        if ($request->lang)
            app()->setLocale($request->lang);

        $request->validate([
            'company_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:14']
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $request->request->add(['user_id' => $user->id]);
            $this->store($request);
        } else {
            $user = $this->registerNewPatient($request);
            $request->request->add(['user_id' => $user->id]);
            $this->store($request);
        }

        return response()->json(['message' => __('Appointment booked successfully!')], 200);
    }

    /**
     * Registers new patient
     *
     * @param Request $request
     * @return App\Models\User
     */
    private function registerNewPatient(Request $request)
    {
        $password = uniqid();
        $data = $request->only(['company_id', 'name', 'email', 'phone']);
        $data['status'] = '1';
        $data['password'] = bcrypt($password);
        $user = User::create($data);
        $role = Role::where('name', 'Patient')->first();
        $user->assignRole([$role->id]);
        $user->companies()->attach($user->company_id);

        Mail::to($user->email)
            ->queue(new NewUserCredential($user, $password));

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PatientAppointment  $patientAppointment
     * @return \Illuminate\Http\Response
     */
    public function show(PatientAppointment $patientAppointment)
    {



        $statuses = AppointmentStatus::get();

        if ((auth()->user()->hasRole('Patient') && auth()->id() != $patientAppointment->user_id) || (auth()->user()->hasRole('Doctor') && auth()->id() != $patientAppointment->doctor_id))
            return redirect()->route('dashboard');
        $doctorDetail = DoctorDetail::all();
        $profile = Storage::files("uploads/patient/{$patientAppointment->id}/profile_picture");
        $profilePicture = count($profile) > 0 ? $profile[0] : null;

        // start of log code
        $logs = UserLogs::where('table_name', 'patient_appointments')->orderBy('id', 'desc')
        ->with('user')
        ->paginate(10);
        // end of log code

        return view('patient-appointment.show', compact('patientAppointment', 'statuses', 'profilePicture', 'doctorDetail' ,'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PatientAppointment  $patientAppointment
     * @return \Illuminate\Http\Response
     */
    public function edit(PatientAppointment $patientAppointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientAppointment  $patientAppointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PatientAppointment $patientAppointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PatientAppointment  $patientAppointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(PatientAppointment $patientAppointment)
    {
        $patientAppointment->delete();
        return redirect()->route('patient-appointments.index')->with('success', trans('Patient Appointment Deleted Successfully'));
    }

    /**
     * makes time slots
     *
     * @param DoctorSchedule $doctorSchedule
     * @return \Illuminate\Http\Response
     */
    private function makeSlots(Request $request, DoctorSchedule $doctorSchedule)
    {
        $appointments = PatientAppointment::where('appointment_date', $request->appointmentDate)->get()->pluck('sequence')->all();
        $openTime = strtotime($doctorSchedule->start_time);
        $closeTime = strtotime($doctorSchedule->end_time);
        $avgAppointmentDuration = 60 * $doctorSchedule->avg_appointment_duration;
        $output = '<option value="">--' . __('Select') . '--</option>';

        $j = 0;
        for ($i = $openTime; $i < $closeTime; $i += $avgAppointmentDuration) {
            $j++;
            if (in_array($j, $appointments))
                continue;
            if ($doctorSchedule->serial_type == 'Timestamp')
                $output .= '<option value="' . $j . '-' . date("H:i", $i) . '-' . date("H:i", $i + $avgAppointmentDuration) . '">' . date("H:i", $i) . ' - ' . date("H:i", $i + $avgAppointmentDuration) . '</option>';
            else
                $output .= '<option value="' . $j . '-' . date("H:i", $i) . '-' . date("H:i", $i + $avgAppointmentDuration) . '">' . $j . '</option>';
        }
        return $output;
    }

    public function ajaxExample(Request $request)
    {
        // Retrieve data from the request
        $data = $request->input('data'); // Assuming 'data' is the variable we are sending

        // Process the data if needed (in this example, we'll just return the same data)

        return response()->json(['data' => $data]);
    }






    /**
     * validates form data
     *
     * @param Request $request
     * @return void
     */
    private function validation(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'doctor_id' => ['required', 'integer', 'exists:users,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_slot' => ['required', 'string', function ($attribute, $value, $fail) {
                $times = explode('-', $value);
                if (sizeof($times) != 3 || !strtotime($times[1]) || !strtotime($times[2])) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
            'problem' => ['nullable', 'string', 'max:1000']
        ]);
    }
}

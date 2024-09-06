<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientAppointment;

class AjaxController extends Controller
{
    public function ajaxExample(Request $request)
    {
        // Retrieve data from the request
        $statusId = $request->input('statusId'); // Assuming 'statusId' is the variable for appointment_status_id
        $appointmentId = $request->input('appointmentId'); // Assuming 'appointmentId' is the identifier
        
        // Update the appointment
        $appointment = PatientAppointment::find($appointmentId);
        
        
        // Update appointment_status_id
        $appointment->appointment_status_id = $statusId;
        $appointment->save();
        
        return response()->json(['message' => 'Appointment status updated successfully.']);
    }
}


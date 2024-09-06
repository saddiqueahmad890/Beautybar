<?php

namespace App\Http\Controllers;

use App\Models\PatientTeeth;
use App\Models\ExamInvestigation;
use App\Models\User;
use App\Models\File;
use App\Models\Company;
use App\Models\PatientAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;


class ExamInvestigationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->export) {
            return $this->doExport($request);
        }

        $examInvestigations = $this->filter($request)->orderBy('id', 'desc')->paginate(10);

        $doctors = User::role('Doctor')->whereHas('doctorexamInvestigation')->get();
        $patients = User::role('Patient')->whereHas('patientexamInvestigation')->get();

        return view('exam-investigation.index', compact('examInvestigations', 'patients', 'doctors'));
    }

    private function filter(Request $request)
    {
        $query = ExamInvestigation::query();

        if ($request->has('examination_number') && !empty($request->input('examination_number'))) {
            $query->where('examination_number', 'like', $request->input('examination_number') . '%');
        }
        if ($request->has('mrn_number') && !empty($request->input('mrn_number'))) {
            $query->whereHas('patient.patientDetails', function ($q) use ($request) {
                $q->where('mrn_number', 'like', $request->input('mrn_number') . '%');
            });
        }
        if ($request->has('patient_id') && !empty($request->input('patient_id'))) {
            $query->where('patient_id', $request->input('patient_id'));
        }
        if ($request->has('doctor_id') && !empty($request->input('doctor_id'))) {
            $query->where('doctor_id', $request->input('doctor_id'));
        }

        return $query;
    }

    public function doExport(Request $request)
    {
        $examInvestigationsQuery = $this->filter($request);
        $examInvestigations = $examInvestigationsQuery->with(['patient', 'doctor'])->get();

        $data = $examInvestigations->map(function ($investigation) {
            return [
                'ID' => $investigation->id,
                'Examination Number' => $investigation->examination_number,
                'Patient Name' => $investigation->patient->name ?? 'N/A',
                'Doctor Name' => $investigation->doctor->name ?? 'N/A',
                'Comments' => $investigation->comments,
                'Patient Appointment ID' => $investigation->patient_appointment_id,
                'Created At' => $investigation->created_at,
                'Updated At' => $investigation->updated_at,
            ];
        })->toArray();

        $headers = [
            'ID', 'Examination Number', 'Patient Name', 'Doctor Name', 'Comments',
            'Patient Appointment ID', 'Created At', 'Updated At'
        ];

        return Excel::download(new GenericExport($data, $headers), 'ExamInvestigations.xlsx');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doctors = User::role('Doctor')->get();
        $patients = User::role('Patient')->with('patientDetails')->get();
        return view('exam-investigation.edit' ,compact('patients','doctors'));    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation($request);
        $data = $request->only(['examination_number','patient_id','patient_appointment_id','doctor_id','comments']);
        $examInvestigation=ExamInvestigation::create($data);
        $examInvestigation['examination_number'] = "EX" ."-". date('y') ."-". date('m') . "-" .$examInvestigation->id;
        $examInvestigation->save();
        return redirect()->route('exam-investigations.edit', $examInvestigation->refresh()->id)->with('success', 'New Examination Created.');    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExamInvestigation  $examInvestigation
     * @return \Illuminate\Http\Response
     */
    public function show(ExamInvestigation $examInvestigation)
    {
        $teeth = PatientTeeth::with('toothIssues')->where('examination_id', $examInvestigation->id)->get();
        $company = Company::find(1);
        $company->setSettings();
        $files = File::where('record_id', $examInvestigation->id)->get();

        return view('exam-investigation.show', compact('examInvestigation', 'company', 'teeth','files'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExamInvestigation  $examInvestigation
     * @return \Illuminate\Http\Response
     */
    public function edit(ExamInvestigation $examInvestigation)
    {
        $teeth_issues = PatientTeeth::where('patient_id', $examInvestigation->patient_id)->with('toothIssues')->get();
        $patientAppointments  = PatientAppointment::get();
        $doctors = User::role('Doctor')->get();
        $patients = User::role('Patient')->get();

        return view('exam-investigation.edit', compact('examInvestigation', 'patients', 'doctors', 'teeth_issues','patientAppointments'));
    }



    public function getToothIssues($examinationId,$patientId, $toothNumber)
    {
    $patientTeeth = PatientTeeth::where('examination_id', $examinationId)->where('patient_id', $patientId)
        ->where('tooth_number', $toothNumber)
        ->first();

    if ($patientTeeth) {
        $toothIssues = $patientTeeth->toothIssues()->get();
        return response()->json($toothIssues);
    } else {
        return response()->json([]);
    }
    }
    public function getTeethIssues($examination_id)
    {
    $teethIssues = PatientTeeth::where('examination_id', $examination_id)->pluck('tooth_number');
    return response()->json($teethIssues);
    }

    public function getTeethFiles(Request $request) {
        $examinationId = $request->query('examination_id');
        $toothNumber = $request->query('tooth_number');
        $response = [
            'files' => []
        ];

        // Get all teeth files for the record, procedure, and tooth number from the database
        $files = File::where('record_id', $examinationId)
                     ->where('child_record_id', $toothNumber)
                     ->where('record_type', 'teeth_files')
                     ->get();

        // Get all unique user IDs from the files
        $userIds = $files->pluck('created_by')->unique();

        // Fetch all users that are referenced in the files
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // Prepare the response data
        foreach ($files as $file) {
            $fileData = [
                'file_name' => $file->file_name,
                'uploaded_by' => isset($users[$file->created_by]) ? $users[$file->created_by]->name : 'Unknown',
                'uploaded_at' => $file->created_at->format('Y-m-d H:i:s')
            ];

            $response['files'][] = $fileData;
        }

        return response()->json($response);
    }

    public function fetchAppointments(Request $request)
    {
        $patientId = $request->input('patient_id');
        $appointments = PatientAppointment::where('user_id', $patientId)->where('appointment_status_id', 2)->get(['id','appointment_number']);
        return response()->json(['appointments' => $appointments]);
    }

    public function fetchDoctors(Request $request)
    {
        $patient_appointment_id = $request->input('patient_appointment_id');
        $doctor_id = PatientAppointment::where('id', $patient_appointment_id)->pluck('doctor_id')->first();

        $doctors = User::where('id', $doctor_id)->get(['id','name']);
        return response()->json(['doctors' => $doctors]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExamInvestigation  $examInvestigation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamInvestigation $examInvestigation)
    {
        $this->validation($request);
        $data = $request->only(['pr_number','patient_id','doctor_id','comments']);
        $examInvestigation->update($data);
        return redirect()->route('exam-investigations.index')->with('success', 'Exam Investogation Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExamInvestigation  $examInvestigation
     * @return \Illuminate\Http\Response
     */

    public function destroy(ExamInvestigation $examInvestigation)
    {
        $examInvestigation->delete();
        return redirect()->route('exam-investigations.index')->with('success', 'Exam Investogation Deleted.');
    }




    public function deleteFiles(Request $request) {
        $fileName = $request->input('fileName');
        $fileType = $request->input('fileType');
        $recordId = $request->input('recordId');
        $tableName = $request->input('table_name');
        $examinationId = $request->input('examinationId');
        $teethNumber = $request->input('teethNumber');
        $child_table = $request->input('child_table');


        $directory = "uploads/{$tableName}/{$recordId}/{$child_table}/{$examinationId}/{$teethNumber}";
        $filePath = $directory . '/' . $fileName;

        try {
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);

                File::where('file_name', $fileName)
                    ->where('record_id', $examinationId)
                    ->where('record_type', $fileType)
                    ->where('table_name', $child_table)
                    ->where('child_record_id', $teethNumber)
                    ->delete();

                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'error' => 'File not found from Controller (EXam).','directory'=>$directory]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'patient_id' => ['required', 'integer'],
            'doctor_id' => ['required', 'integer'],
            'comments' => ['nullable', 'string', 'max:100']
        ]);
    }
}

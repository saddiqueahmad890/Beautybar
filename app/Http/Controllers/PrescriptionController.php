<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Prescription;
use App\Models\User;
use App\Models\PatientMedicineItem;
use App\Models\PatientDiagnosisItem;
use App\Models\DdMedicineType;
use App\Models\DdMedicine;
use App\Models\DdDiagnosis;
use App\Models\ExamInvestigation;
use App\Models\PatientMedicalHistory;
use App\Models\PatientDrugHistory;
use App\Models\PatientDentalHistory;
use App\Models\PatientSocialHistory;
use Illuminate\Http\Request;
use App\Traits\Loggable;
use App\Models\UserLogs;

class PrescriptionController extends Controller
{
    use loggable;
    /**
     * Constructor
     */
    function __construct()
    {
        $this->middleware('permission:prescription-read|prescription-create|prescription-update|prescription-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:prescription-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:prescription-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:prescription-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $prescriptions = $this->filter($request)->orderBy('id', 'desc')->paginate(10);

        if (auth()->user()->hasRole('Doctor')) {
            $doctors = User::role('Doctor')->where('id', auth()->id())->where('status', '1')->get(['id', 'name']);
        } else {
            $doctors = User::role('Doctor')->where('status', '1')->get(['id', 'name']);
        }

        if (auth()->user()->hasRole('Patient')) {
            $patients = User::role('Patient')->where('id', auth()->id())->where('status', '1')->get(['id', 'name']);
        } else {
            $patients = User::role('Patient')->where('status', '1')->get(['id', 'name']);
        }

        return view('prescriptions.index', compact('prescriptions', 'patients', 'doctors'));
    }


    /**
     * Filter function
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    private function filter(Request $request)
    {
        $query = Prescription::query();

        if (auth()->user()->hasRole('Doctor'))
            $query->where('doctor_id', auth()->id());
        elseif ($request->doctor_id)
            $query->where('doctor_id', $request->doctor_id);

        if (auth()->user()->hasRole('Patient'))
            $query->where('user_id', auth()->id());
        elseif ($request->user_id)
            $query->where('user_id', $request->user_id);

        if ($request->prescription_date)
            $query->where('prescription_date', $request->prescription_date);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $patients = User::role('Patient')->where('status', '1')->with('patientDetails')->get(['id', 'name']);
        $patientMedicalHistories = collect();
        $patientDrugHistories = collect();
        $patientDentalHistories = collect();
        $patientSocialHistories = collect();
        $medicineTypes = DdMedicineType::get(['id', 'name']);
        $medicineNames = DdMedicine::get(['id', 'name']);
        $ddDiagnosises = DdDiagnosis::get(['id', 'name']);
        $patientName = '';

        if ($request->user_id) {
            $patientMedicalHistories = PatientMedicalHistory::where('patient_id', $request->user_id)->get();
            $patientDentalHistories = PatientDentalHistory::where('patient_id', $request->user_id)->get();
            $patientSocialHistories = PatientSocialHistory::where('patient_id', $request->user_id)->get();
            $patientDrugHistories = PatientDrugHistory::where('patient_id', $request->user_id)->get();
            $patientName = User::where('id', $request->user_id)->value('name');
        }

        return view('prescriptions.create', compact('patients', 'patientMedicalHistories', 'patientDentalHistories', 'patientDrugHistories', 'patientSocialHistories', 'medicineTypes', 'medicineNames', 'ddDiagnosises', 'patientName'));
    }




    public function fetchexamination(Request $request)
    {
        $userId = $request->input('user_id');
        $examInvestigations = ExamInvestigation::where('patient_id', $userId)->get(['id', 'examination_number']);
        return response()->json(['examInvestigations' => $examInvestigations]);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('Doctor'))
            return redirect()->route('prescriptions.index')->with('error', trans('Only Doctor Can Create Prescription'));

        $this->validation($request);
        $prescriptionData = $request->only(['user_id', 'examination_id', 'note', 'prescription_date']);
        $prescriptionData['doctor_id'] = auth()->id();
        $prescription = Prescription::create($prescriptionData);
        $prescription->prs_number = getDocNumber($prescription->id, 'PRS');
        $prescription->save();


        if ($request->has('medicine_name')) {
            foreach ($request->medicine_name as $key => $medicineName) {
                $medicineItem = new PatientMedicineItem();
                $medicineItem->prescription_id = $prescription->id;
                $medicineItem->medicine_id = $medicineName;
                $medicineItem->medicine_type_id = $request->medicine_type[$key];
                $medicineItem->instruction = $request->instruction[$key];
                $medicineItem->day = $request->day[$key];
                $medicineItem->save();
            }
        }

        if ($request->has('diagnosis')) {
            foreach ($request->diagnosis as $key => $diagnosis) {
                $diagnosisItem = new PatientDiagnosisItem();
                $diagnosisItem->prescription_id = $prescription->id;
                $diagnosisItem->diagnosis_id = $diagnosis;
                $diagnosisItem->instruction = $request->diagnosis_instruction[$key];
                $diagnosisItem->save();
            }
        }
        return redirect()->route('prescriptions.edit', ['prescription' => $prescription->id, 'user_id' => $request->user_id])
            ->with('success', trans('Prescription Stored Successfully'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function show(Prescription $prescription)
    {
        if ((auth()->user()->hasRole('Patient') && auth()->id() != $prescription->user_id)
            || (auth()->user()->hasRole('Doctor') && auth()->id() != $prescription->doctor_id)
        )
            return redirect()->route('dashboard');

        $company = Company::find($prescription->user->company_id);
        $company->setSettings();
        return view('prescriptions.show', compact('company', 'prescription'));
    }


    public function edit(Request $request, Prescription $prescription)
    {
        $medicineTypes = DdMedicineType::get();
        $medicineNames = DdMedicine::get();
        $ddDiagnosises = DdDiagnosis::get();

        // start of log code
        $logs = UserLogs::where('table_name', 'prescriptions')->orderBy('id', 'desc')
            ->with('user')
            ->paginate(10);
        // end of log code

        $patients = User::role('Patient')->where('status', '1')->with('patientDetails')->get(['id', 'name']);
        $prescriptions = Prescription::all();
        $patientMedicalHistories = collect();
        $patientDrugHistories = collect();
        $patientDentalHistories = collect();
        $patientSocialHistories = collect();

        if ($request->user_id) {
            $patientMedicalHistories = PatientMedicalHistory::where('patient_id', $request->user_id)->get();
            $patientDentalHistories = PatientDentalHistory::where('patient_id', $request->user_id)->get();
            $patientSocialHistories = PatientSocialHistory::where('patient_id', $request->user_id)->get();
            $patientDrugHistories = PatientDrugHistory::where('patient_id', $request->user_id)->get();
            $patientName = User::where('id', $request->user_id)->value('name');
        }

        // Get all medicine type IDs
        $medicineTypeIds = $medicineTypes->pluck('id')->toArray();



        return view('prescriptions.edit', array_merge(
            compact(
                'patients',
                'prescriptions',
                'patientMedicalHistories',
                'patientDentalHistories',
                'patientDrugHistories',
                'patientSocialHistories',
                'prescription',
                'medicineNames',
                'ddDiagnosises',
                'medicineTypes',
                'logs',
                'patientName'
            ),
        ));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prescription $prescription)
    {
        if (auth()->id() != $prescription->doctor_id) {
            return redirect()->route('dashboard');
        }

        $this->validation($request);

        $prescriptionData = $request->only([
            'user_id', 'note', 'prescription_date', 'examination_id'
        ]);

        $prescriptionData['doctor_id'] = auth()->id();
        $prescriptionData['examination_id'] = $prescriptionData['examination_id'];


        $prescription->update($prescriptionData);

        PatientMedicineItem::where('prescription_id', $prescription->id)->delete();

        if ($request->has('medicine_name')) {
            foreach ($request->medicine_name as $key => $medicineName) {
                $medicineItem = new PatientMedicineItem();
                $medicineItem->prescription_id = $prescription->id;
                $medicineItem->medicine_id = $medicineName;
                $medicineItem->medicine_type_id = $request->medicine_type[$key];
                $medicineItem->instruction = $request->instruction[$key];
                $medicineItem->day = $request->day[$key];
                $medicineItem->save();
            }
        }

        PatientDiagnosisItem::where('prescription_id', $prescription->id)->delete();

        if ($request->has('diagnosis')) {
            foreach ($request->diagnosis as $key => $diagnosis) {
                $diagnosisItem = new PatientDiagnosisItem();
                $diagnosisItem->prescription_id = $prescription->id;
                $diagnosisItem->diagnosis_id = $diagnosis;
                $diagnosisItem->instruction = $request->diagnosis_instruction[$key];
                $diagnosisItem->save();
            }
        }

        return redirect()->route('prescriptions.edit', ['prescription' => $prescription->id, 'user_id' => $request->user_id])
            ->with('success', trans('Prescription updated Successfully'));
    }



    public function getmedicinestype($medicineId)
    {
        // Fetch the medicine types based on the medicine ID

        $medicine_id = DdMedicine::where('dd_medicine_type', $medicineId)->get(['id', 'name']);

        // Return the medicine types as JSON response
        return response()->json($medicine_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prescription $prescription)
    {
        if (auth()->user()->hasRole('Doctor') && auth()->id() != $prescription->doctor_id)
            return redirect()->route('dashboard');

        $prescription->delete();
        return redirect()->route('prescriptions.index')->with('success', trans('Prescription Deleted Successfully'));
    }

    /**
     * Makes data json
     *
     * @param Request $request
     * @return json
     */

    /**
     * Validation function
     *
     * @param Request $request
     * @return void
     */
    private function validation(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'examination_id' => ['required', 'integer'],
            'medicine_name' => ['nullable', 'array'],
            'medicine_type' => ['nullable', 'array'],
            'instruction' => ['nullable', 'array'],
            'day' => ['nullable', 'array'],
            'diagnosis' => ['nullable', 'array'],
            'diagnosis_instruction' => ['nullable', 'array'],
            'note' => ['nullable', 'string', 'max:1000'],
            'prescription_date' => ['required', 'date']
        ]);
    }
}

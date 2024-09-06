@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('patient-medical-histories.create') }}" class="btn btn-outline btn-info">
                            @lang('Add Patient Medical History')
                        </a>
                    </h3>
                    <h3>
                        <a href="{{ route('patient-medical-histories.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-medical-histories.index') }}">@lang('Patient Medical List')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Show Patient Medical')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">Show Patient ({{ $patient->name }}) Medical History</h3>
                    </div>
                    <div class="card-body">
                        <form id="medicalhistoryForm" action="{{ route('patient-medical-histories.update', $patient->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="patient" value="{{ $patient->id }}">
                            <input type="hidden" name="doctor" value="{{ $patientMedicalHistories[0]->doctor_id }}">

                            <div id="medicalHistoryRows">
                                <div class="tab-pan" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <h3>Medical History</h3>
                                    <div class="row">
                                        @foreach ($patientMedicalHistories as $item)
                                            <div class="col-xl-3 p-3">
                                                <div class="form-group m-0">
                                                    <label>{{ $item->ddMedicalHistory->title }}</label>
                                                    <p>{{ $item->comments }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



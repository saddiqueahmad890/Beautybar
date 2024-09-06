@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="form-group row">
                    <label class="col-md-12 col-form-label"></label>
                    <div class="col-md-12 d-flex align-items-center">
                        <a href="{{ route('patient-details.index') }}" class="btn btn-outline btn-warning mr-2">
                            @lang('Patient List')
                        </a>
                        <a href="{{ route('patient-treatment-plans.index') }}" class="btn btn-outline btn-info">
                            {{ __('All Treatment Plans') }}
                        </a>
                    </div>
                </div>



                <div class="col">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-treatment-plans.index') }}">@lang('Patient Treatment Plans')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Patient Treatment Plan Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Patient Treatment Plan Info')</h3>
                    <div class="card-tools">
                        <a href="{{ route('patient-treatment-plans.edit', $patientTreatmentPlan->id) }}"
                            class="btn btn-info">@lang('Edit')</a>
                        <button id="doPrint" class="btn btn-default"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>

                <div id="print-area" class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="consultancy px-3">
                                <div class="row">
                                    <div class="col-12">
                                        <img src="{{ asset('assets/images/logo.png') }}"
                                            alt="{{ $ApplicationSetting->item_name }}" id="custom-opacity-sidebar"
                                            class="brand-image">
                                        <span
                                            class="brand-text font-weight-light">{{ $ApplicationSetting->item_name }}</span>
                                    </div>
                                </div>

                                <div class="row consultancy-info">
                                    <div class="col-sm-9 consultancy-col">
                                        <strong>@lang('From')</strong>
                                        <address>
                                            <strong style="font-weight: bold;">{{ $company->company_name }}</strong><br>
                                            @if ($company->company_address)
                                                {!! str_replace(['script'], ['noscript'], $company->company_address) !!}
                                            @endif
                                            <strong style="font-weight: bold;">@lang('Email'):</strong>
                                            {{ $company->company_email }}<br>
                                            @if ($company->company_phone)
                                                <strong style="font-weight: bold;">@lang('Phone'):</strong>
                                                {{ $company->company_phone }}
                                            @endif
                                        </address>
                                    </div>

                                    <div class="col-sm-3 consultancy-col">
                                        <strong style="font-weight: bold;">@lang('Date'): </strong>
                                        {{ $patientTreatmentPlan->created_at }}<br>
                                        <strong style="font-weight: bold;">@lang('MRN NUMBER') # </strong>
                                        {{ str_pad($patientTreatmentPlan->patient->patientDetails->mrn_number, 4, '0', STR_PAD_LEFT) }}<br>
                                    </div>
                                </div>
                            </div>


                            <div class="row col-12 mb-3">
                                <div class="col-lg-5 col-md-5">
                                    <div class="bg-info py-2 pl-3">
                                        <strong style="font-weight: bold;">@lang('Patient Details')</strong>
                                    </div>
                                    <div class="row">
                                        <div class="row col-lg-12 pl-3">
                                            <div class="col-sm-5 consultancy-col">
                                                <address>
                                                    <strong style="font-weight: bold;">@lang('Name'):</strong>
                                                    {{ $patientTreatmentPlan->patient->name }}<br>
                                                    @php
                                                        $age = calculateAge($patientTreatmentPlan->patient->dob);
                                                    @endphp

                                                    <strong style="font-weight: bold;">@lang('Age'):</strong>
                                                    {{ $age['years'] }} y <br>
                                                </address>
                                            </div>
                                            <div class="col-sm-7 consultancy-col">
                                                <strong style="font-weight: bold;">@lang('Email'):</strong>
                                                {{ $patientTreatmentPlan->patient->email }}<br>
                                                @if (!empty($patientTreatmentPlan->patient->phone))
                                                    <strong style="font-weight: bold;">@lang('Phone'):</strong>
                                                    {{$patientTreatmentPlan->patient->phone}}<br>
                                                @endif
                                                @if (!empty($patientTreatmentPlan->patient->address))
                                                    <strong style="font-weight: bold;">@lang('Address'):</strong>
                                                    {!! nl2br(e($patientTreatmentPlan->patient->address)) !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="bg-info py-2 pl-3">
                                        <strong style="font-weight: bold;">@lang('Treatment Plan Details')</strong>
                                    </div>
                                    <div class="treatment-col pl-3">
                                        <address>
                                            <strong style="font-weight: bold;">@lang('Plan Number'):</strong>
                                            {{ $patientTreatmentPlan->treatment_plan_number }}<br>
                                            <strong style="font-weight: bold;">@lang('Procedure Number'):</strong>
                                            {{ $patientTreatmentPlan->examinvestigation->examination_number }}<br>
                                        </address>
                                    </div>                                    
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="bg-info py-2 pl-3">
                                        <strong style="font-weight: bold;">Doctor Details</strong>
                                    </div>
                                    <div class="treatment-col pl-3">
                                        <strong style="font-weight: bold;">@lang('Doctor'):</strong>
                                        {{ $patientTreatmentPlan->doctor->name }}<br>
                                        <strong style="font-weight: bold;">@lang('Phone'):</strong>
                                        {{ $patientTreatmentPlan->doctor->phone }}<br>
                                        <strong style="font-weight: bold;">@lang('Address'):</strong>
                                        {!! nl2br(e($patientTreatmentPlan->doctor->address)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($teeth as $tooth)
                        <div class="row tooth-row-{{ $tooth->tooth_number }}">
                            <div class="col-12">
                                <div class="card mb-1">
                                    <div class="card-header bg-info">
                                        <h3 class="card-title">@lang('Tooth Number') {{ $tooth->tooth_number }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <!-- Tooth Issues -->
                                        <div class="tooth-issues mb-0">
                                            @foreach ($tooth->toothIssues as $issue)
                                                <div class="alert alert-light" style="display: inline-block; margin-right: 30px;">
                                                    <h5>{{ $issue->tooth_issue }}</h5>
                                                    {{ $issue->description }}
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Treatment Procedures -->
                                        <div class="table-responsive">
                                            <table class="table table-hover border tooth-treatment">
                                                <thead class="table-secondary">
                                                    <tr>
                                                        <th class="border">@lang('Procedure Category')</th>
                                                        <th class="border">@lang('Procedure')</th>
                                                        <th class="border">@lang('Cost')</th>
                                                        <th class="border">@lang('Status')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($patientTreatmentPlanProcedures->where('tooth_number', $tooth->tooth_number)->isNotEmpty())
                                                        @foreach ($patientTreatmentPlanProcedures->where('tooth_number', $tooth->tooth_number) as $planProcedure)
                                                            <tr class="treatment-plan-inner-row">
                                                                <td class="border col-xl-3">
                                                                    <span>{{ $planProcedure->procedure->ddprocedurecategory->title }}</span>
                                                                </td>
                                                                <td class="border col-xl-3">
                                                                    <span>{{ $planProcedure->procedure->title }}</span>
                                                                </td>
                                                                <td class="border col-xl-2">
                                                                    <span class="cost">{{ $planProcedure->procedure->price }}</span>
                                                                </td>
                                                                <td class="border col-xl-3">
                                                                    <span>{{ $planProcedure->status === 'activated' ? 'Activated' : 'Not Activated' }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr class="treatment-plan-inner-row">
                                                            <td colspan="4" class="text-center">@lang('No treatment procedures found for this tooth.')</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

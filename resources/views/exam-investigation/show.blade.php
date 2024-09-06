@extends('layouts.layout')

@section('content')
<style>
    .image-container {
        width: 100px; /* Adjust width as needed */
        height: 100px; /* Adjust height as needed */
        overflow: hidden; /* This ensures images don't overflow the container */
    }
    .image-container img {
        width: 100%; /* Stretch image to fill container width */
        height: 100%; /* Stretch image to fill container height */
        object-fit: cover; /* Crop image proportionally to fit container */
    }
</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="form-group row">
                    <label class="col-md-12 col-form-label"></label>
                    <div class="col-md-12 d-flex align-items-center">
                        <a href="{{ route('patient-details.index') }}" class="btn btn-outline btn-warning btn-lg">
                            @lang('Patient List')
                        </a>
                        <a href="{{ route('exam-investigations.index') }}" class="btn btn-outline btn-info btn-lg ">
                            {{ __('All Exam Investigation') }}
                        </a>
                    </div>
                </div>



                <div class="col">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('exam-investigations.index') }}">@lang('Patient Exam Investigation')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Patient Exam Investigation Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Patient Exam Investigation Info')</h3>
                    <div class="card-tools">
                        <a href="{{ route('exam-investigations.edit', $examInvestigation->id) }}"
                            class="btn btn-info">@lang('Edit')</a>
                        <button id="doPrint" class="btn btn-default"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>

                <div id="print-area" class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="consultancy p-3 mb-3">
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
                                                {!! str_replace(['script'], ['noscript'], $company->company_address) !!}<br>
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
                                        {{ $examInvestigation->created_at }}<br>
                                        <strong style="font-weight: bold;">@lang('MRN NUMBER') # </strong>
                                        {{ str_pad($examInvestigation->patient->patientDetails->mrn_number, 4, '0', STR_PAD_LEFT) }}<br>
                                    </div>
                                </div>
                            </div>

                            <div class="consultancy p-3 mb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <strong style="font-weight: bold;">@lang('Patient Details')</strong>

                                    </div>
                                </div>
                                <div class="row consultancy-info">
                                    <div class="col-sm-9 consultancy-col">
                                        <address>
                                            <strong style="font-weight: bold;">@lang('Name'):</strong>
                                            {{ $examInvestigation->patient->name }}<br>
                                            @php
                                                $age = calculateAge($examInvestigation->patient->date_of_birth);
                                            @endphp

                                            <strong style="font-weight: bold;">@lang('Age'):</strong>
                                            {{ $age['years'] }} y <br>
                                            {{-- {{ $examInvestigation->patient->date_of_birth }} --}}

                                        </address>
                                    </div>
                                    <div class="col-sm-3 consultancy-col">
                                        <strong style="font-weight: bold;">@lang('Email'):</strong>
                                        {{ $examInvestigation->patient->email }}<br>
                                        @if (!empty($examInvestigation->patient->phone))
                                            <strong style="font-weight: bold;">@lang('Phone'):</strong>
                                            {{$examInvestigation->patient->phone}}<br>
                                        @endif
                                        @if (!empty($examInvestigation->patient->address))
                                            <strong style="font-weight: bold;">@lang('Address'):</strong>
                                            {!! nl2br(e($examInvestigation->patient->address)) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="treatment p-3 mb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <strong style="font-weight: bold;">@lang('Patient Exam Investigation Details')</strong>

                                    </div>
                                </div>
                                <div class="row treatment-info">
                                    <div class="col-sm-9 treatment-col">
                                        <address>
                                            <strong style="font-weight: bold;">@lang('Examination Number'):</strong>
                                            {{ $examInvestigation->examination_number }}<br>
                                            <strong style="font-weight: bold;">@lang('Appointment Number'):</strong>
                                            {{ $examInvestigation->PatientAppointment->appointment_number }}<br>

                                        </address>
                                    </div>
                                    <div class="col-sm-3 treatment-col">
                                        <strong style="font-weight: bold;">@lang('Doctor'):</strong>
                                        {{ $examInvestigation->doctor->name }}<br>
                                        <strong style="font-weight: bold;">@lang('Phone'):</strong>
                                        {{ $examInvestigation->doctor->phone }}<br>
                                        <strong style="font-weight: bold;">@lang('Address'):</strong>
                                        {!! nl2br(e($examInvestigation->doctor->address)) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    @foreach ($teeth as $tooth)
                    <div class="row tooth-row-{{ $tooth->tooth_number }}">
                        <div class="col-12">
                            <div class="card mb-3">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">@lang('Tooth Number') {{ $tooth->tooth_number }}</h3>
                                    <div class="float-right">
                                        <input type="checkbox" id="attach-images-{{ $tooth->tooth_number }}" class="attach-images-checkbox">
                                        <label for="attach-images-{{ $tooth->tooth_number }}">@lang('Remove Images')</label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Tooth Issues -->
                                    <div class="tooth-issues mb-3">
                                        @foreach ($tooth->toothIssues as $issue)
                                            <div class="alert alert-light" style="display: inline-block; margin-right: 30px;">
                                                <h5>{{ $issue->tooth_issue }}</h5>
                                                {{ $issue->description }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="tooth-files mt-3">
                                        @foreach ($files as $file)
                                            @if ($file['child_record_id'] == $tooth->tooth_number)
                                                <div class="image-container" style="display: inline-block; margin-right: 10px; margin-bottom: 10px;">
                                                    <img style="height:100px; width:100px;" src="{{ asset('storage/uploads/patient/'.$examInvestigation->patient->id.'/exam_investigations/'.$examInvestigation->id.'/'.$tooth->tooth_number.'/'.$file['file_name']) }}"  alt="File" class="tooth-image" data-tooth-number="{{ $tooth->tooth_number }}">
                                                </div>
                                            @endif
                                        @endforeach
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const attachImagesCheckboxes = document.querySelectorAll('.attach-images-checkbox');

            attachImagesCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const toothFilesDiv = this.closest('.card').querySelector('.tooth-files');

                    if (this.checked) {
                        toothFilesDiv.style.display = 'none'; // Hide the tooth-files div
                    } else {
                        toothFilesDiv.style.display = 'block'; // Show the tooth-files div
                    }
                });
            });
        });
    </script>
@endsection

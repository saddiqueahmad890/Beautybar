@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="form-group row">
                    <label class="col-md-12 col-form-label"></label>
                    <div class="col-md-12 d-flex align-items-center">
                        <a href="{{ route('patient-details.index') }}" class="btn btn-outline btn-warning btn-lg">
                            @lang('Patient List')
                        </a>
                        <a href="{{ route('consultancey-fees.index') }}" class="btn btn-outline btn-info btn-lg ">
                            {{ __('Fee List') }}
                        </a>
                    </div>
                </div>



                <div class="col">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('consultancey-fees.index') }}">@lang('Consultancy Fee')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Consultancy Fee Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Consultancy Fee Info')</h3>
                    <div class="card-tools">
                        <a href="{{ route('consultancey-fees.edit', $consultanceyFee->id) }}"
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
                                        {{ date('d-m-Y'), $consultanceyFee->date }}<br>
                                        <strong style="font-weight: bold;">@lang('MRN NUMBER') # </strong>
                                        {{ str_pad($patientDetail->patientDetails->mrn_number, 4, '0', STR_PAD_LEFT) }}<br>
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
                                            {{ $patientDetail->name }}<br>
                                            @php
                                                $age = calculateAge($patientDetail->date_of_birth);
                                            @endphp
                                            <strong style="font-weight: bold;">@lang('Age'):</strong>
                                            {{ $age['years'] }} years <br>
                                        </address>
                                    </div>


                                    <div class="col-sm-3 consultancy-col">
                                        <strong style="font-weight: bold;">@lang('Email'):</strong>
                                        {{ $patientDetail->email }}<br>
                                        @if (!empty($patientDetail->phone))
                                            <strong style="font-weight: bold;">@lang('Phone'):</strong>
                                            {{ $patientDetail->phone }}<br>
                                        @endif
                                        @if (!empty($patientDetail->address))
                                            <strong style="font-weight: bold;">@lang('Address'):</strong>
                                            {!! nl2br(e($patientDetail->address)) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="custom-th-width-30">@lang('Description')</th>
                                    <th scope="col" class="custom-th-width-60 text-right">@lang('Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $consultanceyFee->description }}</td>
                                    <td class="text-right">{{ $consultanceyFee->amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="text-right">@lang('Total')</th>
                                        <td class="text-right">{{ $consultanceyFee->amount }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

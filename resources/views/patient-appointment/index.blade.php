@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @can('patient-appointment-create')
                        <h3>
                            <a href="{{ route('patient-appointments.create') }}" class="btn btn-outline btn-info">+ @lang('Customer Appointment')</a>
                            <span class="pull-right"></span>
                        </h3>
                    @endcan
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">@lang('Customer Appointment')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Customer Appointment')</h3>
                    <div class="card-tools">
                        <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if(request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Customer')</label>
                                            <select name="user_id" class="form-control select2" id="user_id">
                                                <option value="">--@lang('Select')--</option>
                                                @foreach ($patients as $patient)
                                                    <option value="{{ $patient->id }}" {{ old('user_id', request()->user_id) == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Doctor')</label>
                                            <select name="doctor_id" class="form-control select2" id="doctor_id">
                                                <option value="">--@lang('Select')--</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}" {{ old('doctor_id', request()->doctor_id) == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Appointment Date')</label>
                                            <input type="text" name="appointment_date" id="appointment_date" class="form-control flatpickr" placeholder="@lang('Appointment Date')" value="{{ old('appointment_date', request()->appointment_date) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Start Date')</label>
                                            <input type="text" name="start_date" id="start_date" class="form-control flatpickr" placeholder="@lang('Start Date')" value="{{ old('start_date', request()->start_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('End Date')</label>
                                            <input type="text" name="end_date" id="end_date" class="form-control flatpickr" placeholder="@lang('End Date')" value="{{ old('end_date', request()->end_date) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if(request()->isFilterActive)
                                            <a href="{{ route('patient-appointments.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th style="text-wrap:nowrap;">Appointment No</th>
                                <th>Employee</th>
                                <th>Customer</th>
                                <th>@lang('Appointment Date')</th>
                                <th>@lang('Appointment Time')</th>
                                <th>@lang('Appointment Status')</th>
                                <th data-orderable="false">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patientAppointments as $patientAppointment)
                                <tr>
                                    <td><span>{{ $patientAppointment->appointment_number }}</span></td>
                                    <td>{{ $patientAppointment->doctor->name }}</td>
                                    <td>{{ $patientAppointment->user->name }}</td>
                                    <td>{{ date($companySettings->date_format, strtotime($patientAppointment->appointment_date)) }}</td>
                                    <td>{{ $patientAppointment->start_time . ' - ' . $patientAppointment->end_time }}</td>
                                    <td>{{ isset($patientAppointment->appointmentstatus->name) ? $patientAppointment->appointmentstatus->name : "-"}}</td>
                                    <td class="responsive-width">
                                        <a href="{{ route('patient-appointments.show', $patientAppointment) }}" class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('View')"><i class="fa fa-eye ambitious-padding-btn"></i></a>
                                        @can('patient-appointments-update')
                                            <a href="{{ route('patient-appointments.edit',$patientAppointment) }}" class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>
                                        @endcan
                                        @can('doctor-schedule-delete')
                                            <a href="#" data-href="{{ route('patient-appointments.destroy', $patientAppointment) }}" class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $patientAppointments->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

@include('layouts.delete_modal')
@endsection

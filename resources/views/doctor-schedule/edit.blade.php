@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('doctor-schedules.create') }}" class="btn btn-outline btn-info">+
                            @lang('Add Schedule')</a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3><a href="{{ route('doctor-schedules.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')</a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('doctor-schedules.index') }}">@lang('Employee Schedule')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Employee Schedule')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Add Employee Schedule ({{ $doctorSchedule->user->name }})</h3>
                </div>
                <div class="card-body">
                    <form id="scheduleForm" class="form-material form-horizontal"
                        action="{{ route('doctor-schedules.update', $doctorSchedule) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_id">@lang('Select Employee') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <select name="user_id"
                                            class="form-control select2 @error('user_id') is-invalid @enderror"
                                            id="user_id" required>
                                            <option value="">--@lang('Select')--</option>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}"
                                                    {{ old('user_id', $doctorSchedule->user_id) == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="weekday">@lang('Select Weekday') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                        </div>
                                        <select name="weekday" class="form-control @error('weekday') is-invalid @enderror"
                                            id="weekday" required>
                                            <option value="">--@lang('Select')--</option>
                                            @foreach (config('constant.weekdays') as $weekday)
                                                <option value="{{ $weekday }}"
                                                    {{ old('weekday', $doctorSchedule->weekday) == $weekday ? 'selected' : '' }}>
                                                    @lang($weekday)</option>
                                            @endforeach
                                        </select>
                                        @error('weekday')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_time">@lang('Start Time') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        </div>
                                        <input type="text" name="start_time" id="start_time"
                                            class="form-control flatpickr-pick-time @error('start_time') is-invalid @enderror"
                                            value="{{ old('start_time', $doctorSchedule->start_time) }}" required>
                                        @error('start_time')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_time">@lang('End Time') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        </div>
                                        <input type="text" name="end_time" id="end_time"
                                            class="form-control flatpickr-pick-time @error('end_time') is-invalid @enderror"
                                            value="{{ old('end_time', $doctorSchedule->end_time) }}" required>
                                        @error('end_time')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="avg_appointment_duration">@lang('Avg Duration') (@lang('minute')) <b
                                            class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-clock"></i></span>
                                        </div>
                                        <select class="form-control @error('avg_appointment_duration') is-invalid @enderror"
                                            name="avg_appointment_duration" id="avg_appointment_duration" required>
                                            @foreach (config('constant.avg_appointment_durations') as $duration)
                                                <option value="{{ $duration }}"
                                                    {{ old('avg_appointment_duration', $doctorSchedule->avg_appointment_duration) == $duration ? 'selected' : '' }}>
                                                    {{ $duration }}</option>
                                            @endforeach
                                        </select>
                                        @error('avg_appointment_duration')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="serial_type">@lang('Serial Type') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-align-center"></i></span>
                                        </div>
                                        <select class="form-control @error('serial_type') is-invalid @enderror"
                                            name="serial_type" id="serial_type" required>
                                            <option value="Timestamp"
                                                {{ old('serial_type', $doctorSchedule->serial_type) == 'Timestamp' ? 'selected' : '' }}>
                                                @lang('Timestamp')</option>
                                        </select>
                                        @error('serial_type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">@lang('Status') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                        </div>
                                        <select class="form-control @error('status') is-invalid @enderror" name="status"
                                            id="status" required>
                                            <option value="1"
                                                {{ old('status', $doctorSchedule->status) === '1' ? 'selected' : '' }}>
                                                @lang('Active')</option>
                                            <option value="0"
                                                {{ old('status', $doctorSchedule->status) === '0' ? 'selected' : '' }}>
                                                @lang('Inactive')</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-8">
                                <input type="submit" value="{{ __('Update') }}"
                                    class="btn btn-outline btn-info btn-lg" />
                                <a href="{{ route('doctor-schedules.index') }}"
                                    class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @php
    $statusMapping = [
        1 => 'Active',
        0 => 'Non Active',
        // Add other statuses if needed
    ];
@endphp
    <div class="container mt-2">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">User Logs</h3>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Table</th>
                        <th>Column</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->user->name }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->table_name }}</td>
                            <td>{{ $log->field_name }}</td>
                            <td>
                                @if ($log->field_name === 'status')
                                    {{ $statusMapping[$log->old_value] ?? $log->old_value }}
                                @else
                                    {{ $log->old_value }}
                                @endif
                            </td>
                            <td>
                                @if ($log->field_name === 'status')
                                    {{ $statusMapping[$log->new_value] ?? $log->new_value }}
                                @else
                                    {{ $log->new_value }}
                                @endif
                            </td>
                            <td>{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

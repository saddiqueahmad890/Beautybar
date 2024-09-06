@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">Attendance</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Attendance List')</h3>
                    <div class="card-tools">
                        <button class="btn btn-default" data-toggle="collapse" href="#filter">
                            <i class="fas fa-filter"></i> @lang('Filter')
                            
                        </button>
                        <a href="{{ route('attendance.export', ['date' => request('date', $today)]) }}" class="btn btn-primary"><i class="fas fa-cloud-download-alt mr-2"></i>Export</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="{{ route('attendance.index') }}" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ request()->name }}" placeholder="@lang('Name')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Date')</label>
                                            <input type="text" name="date" id="date"
                                                class="form-control flatpickr" placeholder="@lang('Date')"
                                                value="{{ old('date', request()->date) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('attendance.index') }}"
                                                class="btn btn-secondary">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="d-flex ">
                        <a href="{{ route('attendance.index', ['date' => $previousDate]) }}" class="btn btn-secondary">
                            &lt;Previous
                        </a>
                        <a href="{{ route('attendance.index', ['date' => $today]) }}" class="btn btn-primary">
                            Today
                        </a>
                        <a href="{{ route('attendance.index', ['date' => $nextDate]) }}" class="btn btn-secondary">
                            Next&gt; 
                        </a>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped" id="laravel_datatable">
                            <thead>
                                <tr>
                                    <th>@lang('Name of User')</th>
                                    <th>@lang('First Check-In')</th>
                                    <th>@lang('Last Check-Out')</th>
                                    <th>@lang('Total Duty Time')</th>
                                    <th>@lang('Date')</th>
                                    <th data-orderable="false">@lang('Actions')</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($doctorsWithAttendance as $data)
                            <tr>
                                <td>{{ $data['doctor_name'] ?? ' ' }}</td> <!-- Display doctor's name -->
                                <td>{{ $data['attendance']->first_check_in ?? ' ' }}</td>
                                <td>{{ $data['attendance']->last_check_out ?? ' ' }}</td>
                                <td>{{ $data['attendance']->total_duty_time ?? ' ' }}</td>
                                <td>{{ $data['attendance']->status ?? '' }}</td>
                                <td>
                                    @if (request()->date == $today) <!-- Check if current date is today -->
                                        <form action="{{ route('attendance.admin-check-in', $data['doctor_id']) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary"
                                                style="{{ $data['attendance'] && $data['attendance']->status == 1 ? 'display: none;' : '' }}">
                                                Check In
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('attendance.admin-check-out', $data['doctor_id']) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger"
                                                style="{{ !$data['attendance'] || $data['attendance']->status == 0 ? 'display: none;' : '' }}">
                                                Check Out
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.delete_modal')
@endsection

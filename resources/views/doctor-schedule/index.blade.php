@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @can('doctor-schedule-create')
                        <h3>
                            <a href="{{ route('doctor-schedules.create') }}" class="btn btn-outline btn-info">+
                                @lang('Add Schedule')</a>
                            <span class="pull-right"></span>
                        </h3>
                    @endcan
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">@lang('Employee Schedule')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Employee Schedule')</h3>
                    <div class="card-tools">
                         <a class="btn btn-primary float-right" target="_blank" href="{{ route('doctor-schedules.index') }}?export=1">
                        <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                    </a>
                        <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i>
                            @lang('Filter')</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ request()->name }}" placeholder="@lang('Name')">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Week Day')</label>
                                            <input type="text" name="weekday" class="form-control"
                                                value="{{ request()->weekday }}" placeholder="@lang('weekday')">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('doctor-schedules.index') }}"
                                                class="btn btn-secondary">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                
                                <th>@lang('Employee Name')</th>
                                <th>@lang('Weekday')</th>
                                <th>@lang('Working Time')</th>
                                <th>@lang('Status')</th>
                                <th data-orderable="false">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctorSchedules as $doctorSchedule)
                                <tr>
                                   
                                    <td>{{ $doctorSchedule->user->name ?? ' ' }}</td>
                                    <td>{{ $doctorSchedule->weekday }}</td>
                                    <td>{{ $doctorSchedule->start_time . ' - ' . $doctorSchedule->end_time }}</td>
                                    <td>
                                        @if ($doctorSchedule->status == '1')
                                            <span class="badge badge-pill badge-success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td class="responsive-width">
                                        <a href="{{ route('doctor-schedules.show', $doctorSchedule) }}"
                                            class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="tooltip" title="@lang('View')"><i
                                                class="fa fa-eye ambitious-padding-btn"></i></a>
                                        @can('doctor-schedule-update')
                                            <a href="{{ route('doctor-schedules.edit', $doctorSchedule) }}"
                                                class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="tooltip" title="@lang('Edit')"><i
                                                    class="fa fa-edit ambitious-padding-btn"></i></a>
                                        @endcan
                                        @can('doctor-schedule-delete')
                                            <a href="#"
                                                data-href="{{ route('doctor-schedules.destroy', $doctorSchedule) }}"
                                                class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i
                                                    class="fa fa-trash ambitious-padding-btn"></i></a>
                                        @endcan

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $doctorSchedules->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.delete_modal')
@endsection

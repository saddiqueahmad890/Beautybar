@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">

                    <h3><a href="{{ route('tasks.create') }}" class="btn btn-outline btn-info"><i>+</i>
                            Create New</a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('tasks.index') }}">All Tasks</a>
                        </li>
                        <li class="breadcrumb-item active">Add New Task</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">All Tasks</h3>
                    <div class="card-tools">
                        <a class="btn btn-primary float-right" target="_blank" href="{{ route('tasks.index') }}?export=1">
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
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Title')</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ request()->title }}" placeholder="@lang('Title')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Assign To')</label>
                                            <input type="text" name="assign_to" class="form-control"
                                                value="{{ request()->assign_to }}" placeholder="@lang('Assign To')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Assign By')</label>
                                            <input type="text" name="assign_by" class="form-control"
                                                value="{{ request()->assign_by }}" placeholder="@lang('Assign By')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Start Date')</label>
                                            <input type="text" name="start_date" id="start_date"
                                                class="form-control flatpickr" placeholder="@lang('Start Date')"
                                                value="{{ old('start_date', request()->start_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('End Date')</label>
                                            <input type="text" name="end_date" id="end_date"
                                                class="form-control flatpickr" placeholder="@lang('End Date')"
                                                value="{{ old('end_date', request()->end_date) }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('tasks.index') }}"
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

                                <th>Assign By</th>
                                <th>Assign To</th>
                                <th>Title</th>
                                <th>Due Date</th>
                                <th>Task Priority</th>
                                <th>Task Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td><span>{{ $task->assignBy->name ?? ' ' }}</span></td>
                                    <td>{{ $task->assignTo->name ?? ' ' }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->due_date }}</td>
                                    <td>{{ $task->taskPriority->title ?? ' ' }}</td>
                                    <td>{{ $task->taskStatus->title ?? ' ' }}</td>
                                    <td>
                                        <a href="{{ route('tasks.show', $task) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip"
                                            title="@lang('View')"><i class="fa fa-eye ambitious-padding-btn"></i></a>

                                        @can('task-delete')
                                            <a href="#" data-href="{{ route('tasks.destroy', $task) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal"
                                                data-target="#myModal" title="@lang('Delete')"><i
                                                    class="fa fa-trash ambitious-padding-btn"></i></a>
                                        @endcan


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $tasks->withQueryString()->links() }}

                </div>
            </div>
        </div>
    </div>


    @include('layouts.delete_modal')
@endsection

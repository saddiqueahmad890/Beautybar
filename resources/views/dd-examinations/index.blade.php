@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @can('examination-create')
                        <h3>
                            <a href="{{ route('dd-examinations.create') }}" class="btn btn-outline btn-info">
                                + @lang('Add Examinations')
                            </a>
                            <span class="pull-right"></span>
                        </h3>
                    @endcan
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Examination List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Examination List')</h3>
                    <div class="card-tools">
                        <button class="btn btn-default" data-toggle="collapse" href="#filter">
                            <i class="fas fa-filter"></i> @lang('Filter')
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->has('isFilterActive')) show @endif">
                        <div class="card-body border">
                            <form action="{{ route('dd-examinations.index') }}" method="get" role="form"
                                autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <examinationel>@lang('Title')</examinationel>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ request()->input('title') }}" placeholder="@lang('Title')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <examinationel>@lang('Description')</examinationel>
                                            <input type="text" name="description" class="form-control"
                                                value="{{ request()->input('description') }}"
                                                placeholder="@lang('description')">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if (request()->has('isFilterActive'))
                                            <a href="{{ route('dd-examinations.index') }}"
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
                                
                                <th>@lang('Title')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($examinations as $examination)
                                <tr>
                                   
                                    <td>{{ $examination->title }}</td>
                                    <td>{{ $examination->description }}</td>
                                        <td>
                                         @if ($examination->status == '1')
                                            <span class="badge badge-pill badge-success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('dd-examinations.show', $examination) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip"
                                            title="@lang('Edit')"><i class="fa fa-eye ambitious-padding-btn"></i></a>
                                        <a href="{{ route('dd-examinations.edit', $examination) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip"
                                            title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>
                                        <a href="#" data-href="{{ route('dd-examinations.destroy', $examination) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal"
                                            data-target="#myModal" title="@lang('Delete')"><i
                                                class="fa fa-trash ambitious-padding-btn"></i></a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $examinations->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.delete_modal')
@endsection

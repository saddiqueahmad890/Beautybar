@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('dd-blood-groups.create') }}" class="btn btn-outline btn-info">+
                            @lang('Add New Blood Group')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">@lang('Dashboard')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Blood Group List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3>@lang('Options List')</h3>
                    <a class="btn btn-primary float-right" target="_blank" href="{{ route('dd-blood-groups.index') }}?export=1">
                        <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                
                                <th>@lang('Name')</th>
                                <th>@lang('Status')</th>
                                <th data-orderable="false">@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bloodGroups as $bloodGroup)
                                <tr>
                                     
                                    <td>{{ $bloodGroup->name }}</td>
                                    <td>
                                         @if ($bloodGroup->status == '1')
                                            <span class="badge badge-pill badge-success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- @can('student-update') --}}
                                        <a href="{{ route('dd-blood-groups.edit', $bloodGroup) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip"
                                            title="@lang('Edit')">
                                            <i class="fa fa-edit ambitious-padding-btn"></i>
                                        </a>
                                        {{-- @endcan --}}
                                        {{-- @can('student-delete') --}}
                                        <a href="#" data-href="{{ route('dd-blood-groups.destroy', $bloodGroup) }}"
                                            class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal"
                                            data-target="#myModal" title="@lang('Delete')">
                                            <i class="fa fa-trash ambitious-padding-btn"></i>
                                        </a>
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $bloodGroups->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.delete_modal')
@endsection
x
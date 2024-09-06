@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- @can('student-create') --}}
                        <h3><a href="{{ route('marital-statuses.create') }}" class="btn btn-outline btn-info">+ @lang('Add New Marital Status')</a>
                            <span class="pull-right"></span>
                        </h3>
                    {{-- @endcan --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Marital Status List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3>@lang('Marital Status List')</h3>
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
                            @foreach ($maritalStatuses as $maritalStatus)
                                <tr>
                                     
                                    <td>{{ $maritalStatus->name }}</td>
                                    <td>
                                         @if ($maritalStatus->status == '1')
                                            <span class="badge badge-pill badge-success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- @can('student-update') --}}
                                            <a href="{{ route('marital-statuses.edit', $maritalStatus) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>
                                        {{-- @endcan --}}
                                        {{-- @can('student-delete') --}}
                                            <a href="#" data-href="{{ route('marital-statuses.destroy', $maritalStatus) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $maritalStatuses->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@include('layouts.delete_modal')
@endsection

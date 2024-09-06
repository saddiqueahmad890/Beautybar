@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @can('patient-detail-create')
                        <h3><a href="{{ route('patient-details.create') }}" class="btn btn-outline btn-info">+
                                @lang('Add Customer')</a>
                            <span class="pull-right"></span>
                        </h3>
                    @endcan
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Customer List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Customer List') </h3>
                    <div class="card-tools">

                        <a class="btn btn-primary" target="_blank" href="{{ route('patient-details.index') }}?export=1">
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
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ request()->name }}" placeholder="@lang('Name')">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('CR Number')</label>
                                            <input type="text" name="mrn_number" class="form-control"
                                                value="{{ request()->mrn_number }}" placeholder="@lang('CR_number')">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Phone')</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ request()->phone }}" placeholder="@lang('Phone')">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Email')</label>
                                            <input type="text" name="email" class="form-control"
                                                value="{{ request()->email }}" placeholder="@lang('Email')">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('City')</label>
                                            <input type="text" name="city" class="form-control"
                                                value="{{ request()->city }}" placeholder="@lang('City')">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Start Date')</label>
                                            <input type="text" name="start_date" id="start_date"
                                                class="form-control flatpickr" placeholder="@lang('Start Date')"
                                                value="{{ old('start_date', request()->start_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
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
                                            <a href="{{ route('patient-details.index') }}"
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
                                <th style="width: 50px;">Profile</th>
                                <th style="width: 150px;">@lang('Name')</th>
                                <th style="width: 100px;">@lang('CR NO')</th>
                                <th style="width: 120px;">@lang('Phone')</th>
                                <th style="width: 200px;">@lang('Email')</th>
                                <th style="width: 100px;">@lang('City')</th>
                                <th style="width: 120px;">@lang('Reg# Date')</th>
                                <th style="width: 100px;">@lang('Actions')</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patientDetails as $patientDetail)
                                <tr>
                                    <td class="col-2">
                                        @if ($patientDetail->profilePicture)
                                            <img class="profile-user-img img-fluid img-circle"
                                                src="{{ asset('storage/' . $patientDetail->profilePicture) }}"
                                                alt="Profile Picture"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;" />
                                        @else
                                            <img class="profile-user-img img-fluid img-circle"
                                                src="{{ asset('assets/images/profile/male.png') }}"
                                                alt="Default Profile Picture" class="img-thumbnail"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;" />
                                        @endif
                                    </td>
                                    <td>{{ $patientDetail->name }}</td>
                                    <!-- Profile Picture Modal -->
                                    <div id="profilePicModal" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $patientDetail->name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <img style="width:300px; height:300px;text-align:center;display:block;margin:0 auto;"
                                                        id="profilePicModalImg" src="" alt="Profile Picture"
                                                        style="width: 100%;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td>{{ isset($patientDetail->patientDetails->mrn_number) ? $patientDetail->patientDetails->mrn_number : '-' }}
                                    </td>
                                    <td>{{ $patientDetail->phone }}</td>
                                    <td>{{ $patientDetail->email ? $patientDetail->email : '-' }}
                                    </td>
                                    <td>{{ $patientDetail->patientDetails ? $patientDetail->patientDetails->city : '-' }}
                                    </td>
                                    <td>{{ $patientDetail->created_at->format('d-M-y') }}</td>

                                    <td class="col-2 responsive-width">

                                        <a href="{{ route('patient-details.show', $patientDetail) }}"
                                            class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="tooltip" title="@lang('View')">
                                            <i class="fa fa-eye ambitious-padding-btn"></i>
                                        </a>
                                        @can('patient-detail-update')
                                            <a href="{{ route('patient-details.edit', $patientDetail) }}"
                                                class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="tooltip" title="@lang('Edit')">
                                                <i class="fa fa-edit ambitious-padding-btn"></i>
                                            </a>
                                        @endcan
                                        @can('patient-detail-delete')
                                            <a href="#"
                                                data-href="{{ route('patient-details.destroy', $patientDetail) }}"
                                                class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="modal" data-target="#myModal" title="@lang('Delete')">
                                                <i class="fa fa-trash ambitious-padding-btn"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $patientDetails->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.profile-user-img').on('click', function() {
                var imgSrc = $(this).attr('src');
                $('#profilePicModalImg').attr('src', imgSrc);
                $('#profilePicModal').modal('show');
            });
        });
    </script>
    @include('layouts.delete_modal')
@endsection

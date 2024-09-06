@extends('layouts.layout')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 d-flex">
                <h3 class="mr-2">
                    <a href="{{ route('patient-dental-histories.create') }}" class="btn btn-outline btn-info">+
                        @lang('Add Patient Dental History')
                    </a>
                </h3>
                <h3>
                    <a href="{{ route('patient-dental-histories.index') }}" class="btn btn-outline btn-info">
                        <i class="fas fa-eye"></i> @lang('View All')
                    </a>
                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('patient-dental-histories.index') }}">@lang('Patient Dental List')</a>
                    </li>
                    <li class="breadcrumb-item active">@lang('Edit Patient Dental History')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Edit Patient ({{ $patient->name }}) Dental History</h3>
            </div>
            <div class="container-fluid p-0">
                <form id="dentalhistoryForm" action="{{ route('patient-dental-histories.update', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="patient" value="{{ $patient->id }}">
                    <input type="hidden" name="doctor" value="{{ $patientDentalHistories[0]->doctor_id }}">



                    <div class="row">
                        @foreach ($ddDentalHistories as $item)
                        <div class="col-xl-3 p-3">
                            <div class="form-group m-0">
                                <input type="checkbox" id="title_{{ $item->id }}" name="dental_histories[{{ $item->id }}][checked]"
                                    {{ $patientDentalHistories->contains('dd_dental_history_id', $item->id) ? 'checked' : '' }}>
                                <label for="title_{{ $item->id }}">{{ $item->title }}</label>
                                <input type="text" class="form-control mt-2" id="details_{{ $item->id }}" name="dental_histories[{{ $item->id }}][comments]"
                                    value="{{ $patientDentalHistories->where('dd_dental_history_id', $item->id)->first()->comments ?? '' }}">
                                <input type="hidden" value="{{ $item->id }}" name="dental_histories[{{ $item->id }}][title_id]">
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <div class="col-md-8">
                                    <input type="submit" value="{{ __('Update') }}" class="btn btn-outline btn-info btn-lg" />
                                    <a href="{{ route('patient-dental-histories.index') }}" class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                        <td>{{ $log->old_value }}</td>
                        <td>{{ $log->new_value }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Disable all text inputs initially
            $('input[type="text"]').prop('disabled', true);

            // Enable/disable text inputs based on checkbox state
            $('input[type="checkbox"]').change(function() {
                var inputBox = $(this).siblings('input[type="text"]');
                inputBox.prop('disabled', !$(this).prop('checked'));
            });

            // Trigger change event on page load to sync initial state
            $('input[type="checkbox"]').each(function() {
                $(this).change();
            });
        });
    </script>
@endpush


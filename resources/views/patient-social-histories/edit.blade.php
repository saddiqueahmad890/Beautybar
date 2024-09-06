@extends('layouts.layout')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 d-flex">
                <h3 class="mr-2">
                    <a href="{{ route('patient-social-histories.create') }}" class="btn btn-outline btn-info">+
                        @lang('Add Patient Social History')
                    </a>
                </h3>
                <h3>
                    <a href="{{ route('patient-social-histories.index') }}" class="btn btn-outline btn-info">
                        <i class="fas fa-eye"></i>  @lang('View All')
                    </a>
                </h3>

            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('patient-social-histories.index') }}">@lang('Patient Social List')</a>
                    </li>
                    <li class="breadcrumb-item active">@lang('Edit Patient  Social History')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">Edit Patient ({{ $patient->name }}) Social History</h3>
            </div>
            <div class="container-fluid p-0">
                <form id="socialhistoryForm" action="{{ route('patient-social-histories.update', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="patient" value="{{ $patient->id }}">
                    <input type="hidden" name="doctor" value="{{ $patientSocialHistories[0]->doctor_id }}">





                    <div class="row">
                        @foreach($ddSocialHistories as $item)
                        <div class="col-xl-3 p-3">
                            <div class="form-group m-0">
                                <input type="checkbox" id="title_{{ $item->id }}" name="social_histories[{{ $item->id }}][checked]" {{ $patientSocialHistories->contains('dd_social_history_id', $item->id) ? 'checked' : '' }}>
                                <label for="title_{{ $item->id }}">{{ $item->title }}</label>
                                @if ($patientSocialHistories->contains('dd_social_history_id', $item->id))
                                    <input type="text" class="form-control mt-2" id="details_{{ $item->id }}" name="social_histories[{{ $item->id }}][comments]" value="{{ $patientSocialHistories->where('dd_social_history_id', $item->id)->first()->comments }}">
                                    <input type="hidden" value="{{ $item->id }}" name="social_histories[{{ $item->id }}][title_id]">
                                @else
                                    <input type="text" class="form-control mt-2" id="details_{{ $item->id }}" name="social_histories[{{ $item->id }}][comments]" value="">
                                    <input type="hidden" value="{{ $item->id }}" name="social_histories[{{ $item->id }}][title_id]">
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <div class="col-md-8">
                                    <input type="submit" value="{{ __('Update') }}" class="btn btn-outline btn-info btn-lg"/>
                                    <a href="{{ route('patient-social-histories.index') }}" class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
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

            if ($(this).is(':checked')) {
                inputBox.prop('disabled', false);
            } else {
                inputBox.prop('disabled', true);
            }
        });
    });
</script>
@endpush

@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <a href="{{ route('patient-treatment-plans.index') }}" class="btn btn-outline btn-info">
                        @lang('View All')</a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                href="{{ route('patient-treatment-plans.index') }}">@lang('Treatment Plans')</a></li>
                        <li class="breadcrumb-item active">@lang('Add New Plan')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row treatment-plan-row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add New Treatment Plan')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('patient-treatment-plans.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="patient">@lang('Select Patient')</label>
                                        <select class="form-control @error('patient') is-invalid @enderror" id="patient" name="patient">
                                            <option value="">@lang('Select Patient')</option>
                                            @foreach ($patient as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->patientDetails->mrn_number ?? ' ' }}</option>
                                            @endforeach
                                        </select>
                                        @error('patient')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="examination_id">@lang('Select Examination')</label>
                                        <select class="form-control @error('examination_id') is-invalid @enderror" id="examination_id" name="examination_id">
                                            <option value="">@lang('Select Teeth Examination')</option>

                                        </select>
                                        @error('examination_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="doctor">@lang('Select Doctor')</label>
                                        <select class="form-control @error('doctor') is-invalid @enderror" id="doctor"
                                            name="doctor">
                                            <option value="">@lang('Select Doctor')</option>
                                              @foreach ($doctor as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('doctor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <label for="comments">@lang('Comments')</label>
                                        <textarea rows="1" name="comments" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status">@lang('Status')</label>
                                        <select name="status" class="form-control">
                                            <option value="1">@lang('Active')</option>
                                            <option value="0">@lang('Inactive')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="submit" value="{{ __('Submit') }}"
                                            class="btn btn-outline btn-info btn-lg" />
                                        <a href="{{ route('patient-treatment-plans.index') }}"
                                            class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



<script>
    $(document).ready(function(){

        $('#patient').on('change', function() {
            var patientId = $(this).val();
            $('#tooth_id').html('');
            $.ajax({
                url: '{{ route("fetch.procedures") }}',
                type: 'GET',
                data: { patient_id: patientId },
                success: function(data) {
                    var procedures = data.procedures;
                    var options = '<option value="" disabled selected>Select Examination</option>';
                    $.each(procedures, function(index, procedure) {
                        options += '<option value="' + procedure.id + '">' + procedure.examination_number + '</option>';
                    });
                    $('#examination_id').html(options);
                },
                error: function(xhr, status, error) {
                    alert('Failed to fetch Teeth Procedures! Please try again.');
                }
            });
        });

        $('#examination_id').on('change', function() {
            var examinationId = $(this).val();
            $.ajax({
                url: '{{ route("fetch.teeth") }}',
                type: 'GET',
                data: { examination_id: examinationId },
                success: function(data) {
                    var doctor = data.doctorDetails;
                    doctor_value = '<option value="' + doctor.id + '">' + doctor.name + '</option>';
                    $('#doctor').html(doctor_value);
                },
                error: function(xhr, status, error) {
                    alert('Failed to fetch Doctor! Please try again.');
                }
            });
        });
    });
</script>
@endsection

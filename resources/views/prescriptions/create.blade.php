@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <meta name="base-url" content="{{ url('/') }}">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <a href="{{ route('prescriptions.index') }}" class="btn btn-outline btn-info"><i class="fas fa-eye"></i>
                        @lang('View All')</a>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('prescriptions.index') }}">@lang('Prescription')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Prescription')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Prescription')</h3>
                </div>
                <div class="card-body">
                    <form class="form-material form-horizontal" action="{{ route('prescriptions.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_id">@lang('Select Patient') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-injured"></i></span>
                                        </div>
                                        <select name="user_id"
                                            class="form-control select2 @error('user_id') is-invalid @enderror"
                                            id="user_id" required>
                                            <option value="">--@lang('Select')--</option>
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}"
                                                    {{ old('user_id') == $patient->id ? 'selected' : '' }}>
                                                    {{ $patient->name }} - {{ $patient->patientDetails->mrn_number ?? ' ' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="examination_id">@lang('Select Examination')</label>
                                <select class="form-control @error('examination_id') is-invalid @enderror"
                                    id="examination_id" name="examination_id">
                                    <option value="">@lang('Select Examination')</option>
                                </select>
                                @error('examination_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Prescription Date') <b class="ambitious-crimson">*</b></label>
                                    <input type="text" name="prescription_date" id="prescription_date"
                                        class="form-control flatpickr @error('prescription_date') is-invalid @enderror"
                                        placeholder="@lang('Prescription Date')"
                                        value="{{ old('prescription_date', date('Y-m-d')) }}" required>
                                    @error('prescription_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                       

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="t1" class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">@lang('Medicine Type')</th>
                                                <th scope="col">@lang('Medicine Name')</th>
                                                <th scope="col">@lang('Instruction')</th>
                                                <th scope="col">@lang('Days')</th>
                                                <th scope="col" class="custom-white-space">@lang('Add / Remove')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (old('medicine_name'))
                                                @foreach (old('medicine_name') as $key => $value)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="medicine_type[]"
                                                                class="form-control"
                                                                value="{{ old('medicine_type')[$key] }}"
                                                                placeholder="@lang('Medicine Type')">

                                                        </td>
                                                        <td>
                                                            <input type="text" name="medicine_name[]"
                                                                class="form-control"
                                                                value="{{ old('medicine_name')[$key] }}"
                                                                placeholder="@lang('Medicine Name')">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="instruction[]"
                                                                class="form-control"
                                                                value="{{ old('instruction')[$key] }}"
                                                                placeholder="@lang('Instructions')">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="day[]" class="form-control"
                                                                value="{{ old('day')[$key] }}"
                                                                placeholder="@lang('Days')">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-info m-add"><i
                                                                    class="fas fa-plus"></i></button>
                                                            <button type="button" class="btn btn-info m-remove"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tbody id="medicine">
                                            <tr>
                                                <td>
                                                    <select name="medicine_type[]"
                                                        class="form-control  @error('medicine_type') is-invalid @enderror"
                                                        id="medicine_type">
                                                        <option value="" disabled selected>Select Medicine Type
                                                        </option>
                                                        @foreach ($medicineTypes as $medicineType)
                                                            <option value="{{ $medicineType->id }}">
                                                                {{ $medicineType->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="medicine_name[]"
                                                        class="form-control  @error('medicine_name') is-invalid @enderror"
                                                        id="medicine_name">
                                                        <option value="" disabled selected>Select Medicine</option>

                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="instruction[]" class="form-control"
                                                        value="" placeholder="@lang('Instructions')">
                                                </td>
                                                <td>
                                                    <input type="text" name="day[]" class="form-control"
                                                        value="" placeholder="@lang('Days')">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info m-add"><i
                                                            class="fas fa-plus"></i></button>
                                                    <button type="button" class="btn btn-info m-remove"><i
                                                            class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="bg-info">
                                            <tr>
                                                <th scope="col">@lang('Diagnosis')</th>
                                                <th scope="col">@lang('Instruction')</th>
                                                <th scope="col" class="custom-white-space custom-width-120">
                                                    @lang('Add / Remove')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (old('diagnosis'))
                                                @foreach (old('diagnosis') as $key => $value)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="diagnosis[]"
                                                                class="form-control "
                                                                value="{{ old('diagnosis')[$key] }}"
                                                                placeholder="@lang('Diagnosis')">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="diagnosis_instruction[]"
                                                                class="form-control "
                                                                value="{{ old('diagnosis_instruction')[$key] }}"
                                                                placeholder="@lang('Instruction')">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-info d-add"><i
                                                                    class="fas fa-plus"></i></button>
                                                            <button type="button" class="btn btn-info d-remove"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tbody id="diagnosis">
                                            <tr>
                                                <td>
                                                    <select name="diagnosis[]"
                                                        class="form-control select2 @error('diagnosis') is-invalid @enderror"
                                                        id="diagnosis">
                                                        <option value="" disabled selected>Select Diagnosis
                                                        </option>
                                                        @foreach ($ddDiagnosises as $ddDiagnosis)
                                                            <option value="{{ $ddDiagnosis->id }}">
                                                                {{ $ddDiagnosis->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="diagnosis_instruction[]"
                                                        class="form-control" value=""
                                                        placeholder="@lang('Instruction')">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info d-add"><i
                                                            class="fas fa-plus"></i></button>
                                                    <button type="button" class="btn btn-info d-remove"><i
                                                            class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note">@lang('Note')</label>
                                    <div class="input-group mb-3">
                                        <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="4"
                                            placeholder="@lang('Note')">{{ old('note') }}</textarea>
                                        @error('note')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8">
                                        <input type="submit" value="{{ __('Submit') }}"
                                            class="btn btn-outline btn-info btn-lg" />
                                        <a href="{{ route('prescriptions.index') }}"
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
        function setupTeethProceduresDropdown() {
            $(document).ready(function() {
                function getUrlParameter(name) {
                    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                    var results = regex.exec(location.search);
                    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
                }
            });
        }



        setupTeethProceduresDropdown();

        $(document).ready(function() {
            // Check if there is a user ID and procedures in local storage
            var storedUserId = localStorage.getItem('user_id');
            if (storedUserId) {
                $('#user_id').val(storedUserId);
                fetchProcedures(storedUserId);
            }

            // Fetch procedures on page load
            function fetchProcedures(userId) {
                $.ajax({
                    url: '{{ route('fetchexamination') }}',
                    type: 'GET',
                    data: {
                        user_id: userId
                    },
                    success: function(data) {
                        var examInvestigations = data.examInvestigations;
                        var options = '<option value="" disabled selected>Select Procedure</option>';
                        $.each(examInvestigations, function(index, examInvestigation) {
                            options += '<option value="' + examInvestigation.id + '">' +
                                examInvestigation
                                .examination_number + '</option>';
                        });
                        $('#examination_id').html(options);

                        // Store procedures in local storage
                        localStorage.setItem('procedures', JSON.stringify(examInvestigations));
                    },
                    error: function(xhr, status, error) {
                        alert('Failed to fetch Teeth Procedures! Please try again.');
                    }
                });
            }

            // Event listener for user ID change
            $('#user_id').on('change', function() {
                var userId = $(this).val();

                // Store the user ID in local storage
                localStorage.setItem('user_id', userId);

                fetchProcedures(userId);
            });

            // Function to populate procedures from local storage
            function populateProcedures() {
                var storedexamInvestigations = JSON.parse(localStorage.getItem('examInvestigations'));
                if (storedexamInvestigations) {
                    var options = '<option value="" disabled selected>Select Procedure</option>';
                    $.each(storedexamInvestigations, function(index, storedexamInvestigation) {
                        options += '<option value="' + storedexamInvestigation.id + '">' +
                            storedexamInvestigation.examination_number + '</option>';
                    });
                    $('#examination_id').html(options);
                }
            }

            // Call the function to populate procedures if they exist in local storage
            populateProcedures();
        });
    </script>




@endsection
@push('footer')
    <script src="{{ asset('assets/js/custom/prescription.js') }}"></script>
@endpush

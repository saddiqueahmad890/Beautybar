@extends('layouts.layout')

@section('content')

    <section class="content-header">
        <meta name="base-url" content="{{ url('/') }}">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <div class="col-sm-6 d-flex">
                        <h3 class="mr-2"><a href="{{ route('prescriptions.create') }}" class="btn btn-outline btn-info">+
                                @lang('Prescription')</a>
                            <span class="pull-right"></span>
                        </h3>
                        <h3>
                            <a href="{{ route('prescriptions.index') }}" class="btn btn-outline btn-info"><i
                                    class="fas fa-eye"></i> @lang('View All')</a>

                        </h3>
                    </div>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('prescriptions.index') }}">@lang('Prescription')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Prescription')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Edit Prescription')</h3>
                </div>
                <div class="card-body">
                    <form class="form-material form-horizontal" action="{{ route('prescriptions.update', $prescription) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="user_id">@lang('Select Customer') <b class="ambitious-crimson">*</b></label>
                                    <select name="user_id" id="user_id"
                                        class="form-control custom-width-100 select2 @error('user_id') is-invalid @enderror"
                                        required>
                                        <option value="">--@lang('Select')--</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}"
                                                @if ($patient->id == $prescription->user_id) selected @endif>
                                                {{ $patient->name }}
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


                            <div class="col-md-3">
                                <label for="examination_id">@lang('Select Examination')</label>
                                <select class="form-control" id="examination_id" name="examination_id">
                                    <option value="">Select Examination</option>
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
                                        value="{{ old('prescription_date', $prescription->prescription_date) }}" required>
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
                                                                placeholder="Medicine Type">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="medicine_name[]"
                                                                class="form-control"
                                                                value="{{ old('medicine_name')[$key] }}"
                                                                placeholder="Medicine Name">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="instruction[]"
                                                                class="form-control"
                                                                value="{{ old('instruction')[$key] }}"
                                                                placeholder="Instructions">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="day[]" class="form-control"
                                                                value="{{ old('day')[$key] }}" placeholder="Days">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-info m-add"><i
                                                                    class="fas fa-plus"></i></button>
                                                            <button type="button" class="btn btn-info m-remove"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach ($prescription->patientmedicineitem as $item)
                                                    <tr>
                                                        <td>
                                                            <select name="medicine_type[]"
                                                                class="form-control select2 @error('medicine_type') is-invalid @enderror">
                                                                <option value="" disabled>Select Medicine Type
                                                                </option>
                                                                @foreach ($medicineTypes as $type)
                                                                    <option value="{{ $type->id }}"
                                                                        {{ $item->medicine_type_id == $type->id ? 'selected' : '' }}>
                                                                        {{ $type->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <select name="medicine_name[]"
                                                                class="form-control select2 @error('medicine_name') is-invalid @enderror">
                                                                <option value="" disabled>Select Medicine</option>
                                                                @foreach ($medicineNames as $medicineName)
                                                                    <option value="{{ $medicineName->id }}"
                                                                        {{ $item->medicine_id == $medicineName->id ? 'selected' : '' }}>
                                                                        {{ $medicineName->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="instruction[]"
                                                                class="form-control" value="{{ $item->instruction }}"
                                                                placeholder="@lang('Instructions')">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="day[]" class="form-control"
                                                                value="{{ $item->day }}"
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
                                        <tbody id="medicine">
                                            <tr>
                                                <td>
                                                    <select name="medicine_type[]"
                                                        class="form-control  @error('medicine_type') is-invalid @enderror"
                                                        id="medicine_type">
                                                        <option value="" disabled selected>Select Medicine Type
                                                        </option>
                                                        @foreach ($medicineTypes as $medicineTypes)
                                                            <option value="{{ $medicineTypes->id }}">
                                                                {{ $medicineTypes->name }}</option>
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
                                                            <input type="text" name="diagnosis[]" class="form-control"
                                                                value="{{ old('diagnosis')[$key] }}"
                                                                placeholder="Diagnosis">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="diagnosis_instruction[]"
                                                                class="form-control"
                                                                value="{{ old('diagnosis_instruction')[$key] }}"
                                                                placeholder="Instruction">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-info d-add"><i
                                                                    class="fas fa-plus"></i></button>
                                                            <button type="button" class="btn btn-info d-remove"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach ($prescription->patientdiagnosisitem as $item)
                                                    <tr>
                                                        <td>
                                                            <select name="diagnosis[]"
                                                                class="form-control  @error('diagnosis') is-invalid @enderror">
                                                                <option value="" disabled>Select Diagnosis</option>
                                                                @foreach ($ddDiagnosises as $diagnosis)
                                                                    <option value="{{ $diagnosis->id }}"
                                                                        {{ $item->diagnosis_id == $diagnosis->id ? 'selected' : '' }}>
                                                                        {{ $diagnosis->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="diagnosis_instruction[]"
                                                                class="form-control" value="{{ $item->instruction }}"
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
                                        <tbody id="diagnosis">
                                            <tr>
                                                <td>
                                                    <select name="diagnosis[]"
                                                        class="form-control  @error('diagnosis') is-invalid @enderror"
                                                        id="diagnosis">
                                                        <option value="" disabled selected>Select Diagnosis</option>
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
                                            placeholder="Note">{{ old('note', $prescription->note) }}</textarea>
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
                                        <input type="submit" value="@lang('Update')"
                                            class="btn btn-outline btn-info btn-lg" />
                                        <a href="{{ route('prescriptions.index') }}"
                                            class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
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


    <script>
        $(document).ready(function() {
            // Function to update URL with user_id
            $(document).on("change", "#user_id", function() {
                let patientId = $("#user_id").val();
                let url = window.location.href.split(/[?#]/)[0];
                window.location.href = url + "?user_id=" + patientId;
            });

            // Function to handle adding and removing medicine rows
            let medicine = $("#medicine").html();
            $(document).on("click", ".m-add", function() {
                $("#medicine").append(medicine);
            });

            $(document).on("click", ".m-remove", function() {
                $(this).parent().parent().remove();
            });

            // Function to handle adding and removing diagnosis rows
            let diagnosis = $("#diagnosis").html();
            $(document).on("click", ".d-add", function() {
                $("#diagnosis").append(diagnosis);
            });

            $(document).on("click", ".d-remove", function() {
                $(this).parent().parent().remove();
            });

            // Get the base URL from the meta tag
            var baseUrl = $('meta[name="base-url"]').attr("content");

            // When the medicine type dropdown changes, populate medicine name dropdown
            $("body").on("change", 'select[name="medicine_type[]"]', function() {
                var medicineTypeId = $(this).val();
                var $medicineNameDropdown = $(this).closest("tr").find('select[name="medicine_name[]"]');

                // Clear the medicine name dropdown
                $medicineNameDropdown.empty();

                // Make an AJAX request to fetch medicines by type
                $.ajax({
                    url: baseUrl + "/getmedicinestype/" + medicineTypeId,
                    type: "GET",
                    success: function(data) {
                        // Populate the medicine name dropdown with the received data
                        $.each(data, function(key, value) {
                            $medicineNameDropdown.append(
                                '<option value="' + value.id + '">' + value.name +
                                "</option>"
                            );
                        });
                    },
                });
            });

            // Function to fetch procedures based on user_id and populate dropdown
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

                // Fetch procedures and populate dropdown
                fetchProcedures(userId);
            });

            // Function to populate procedures dropdown from local storage
            function populateProcedures() {
                var storedProcedures = JSON.parse(localStorage.getItem('procedures'));
                if (storedProcedures) {
                    var options = '<option value="" disabled selected>Select Examination</option>';
                    $.each(storedProcedures, function(index, procedure) {
                        options += '<option value="' + procedure.id + '">' + procedure.examination_number +
                            '</option>';
                    });
                    $('#examination_id').html(options);
                }
            }

            // Call function to populate procedures dropdown if data exists in local storage
            populateProcedures();
        });

        $(document).ready(function() {
            // Function to fetch procedures and populate dropdown
            function fetchProcedures(userId, selectedProcedureId) {
                $.ajax({
                    url: '{{ route('fetchexamination') }}',
                    type: 'GET',
                    data: {
                        user_id: userId
                    },
                    success: function(data) {
                        var examInvestigations = data.examInvestigations;
                        var options = '<option value="" disabled>Select Procedure</option>';
                        $.each(examInvestigations, function(index, examInvestigation) {
                            options += '<option value="' + examInvestigation.id + '"';
                            if (examInvestigation.id == selectedProcedureId) {
                                options += ' selected';
                            }
                            options += '>' + examInvestigation.examination_number + '</option>';
                        });
                        $('#examination_id').html(options);
                    },
                    error: function(xhr, status, error) {
                        alert('Failed to fetch Teeth Procedures! Please try again.');
                    }
                });
            }

            // Function to get URL parameter
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            // On page load, fetch procedures for the selected user (if user_id parameter exists)
            var userId = getUrlParameter('user_id');
            if (userId) {
                fetchProcedures(userId, '{{ $prescription->examination_id }}');
            }

            // Event listener for user ID change
            $('#user_id').on('change', function() {
                var userId = $(this).val();
                fetchProcedures(userId, ''); // Clear selectedProcedureId to reset dropdown
            });
        });
    </script>
@endsection
@push('footer')
    {{-- <script src="{{ asset('assets/js/custom/prescription.js') }}"></script> --}}
@endpush

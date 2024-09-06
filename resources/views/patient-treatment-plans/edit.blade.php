@extends('layouts.layout')

@section('content')

<section class="content-header">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 d-flex">
                <h3 class="mr-2">
                    <a href="{{ route('patient-treatment-plans.create') }}" class="btn btn-outline btn-info">+
                        @lang('Add New Plan')</a>
                    <span class="pull-right"></span>
                </h3>
                <h3>
                    <a href="{{ route('patient-treatment-plans.index') }}" class="btn btn-outline btn-info"><i class="fas fa-eye"></i>  @lang('View All')</a>

                </h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('patient-treatment-plans.index') }}">@lang('Treatment Plans')</a></li>
                    <li class="breadcrumb-item active">@lang('Edit Plan')</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row treatment-plan-row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">@lang('Treatment Plan')</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('patient-treatment-plans.update',$patientTreatmentPlan) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="demo_value" id="demo_value" value={{ $patientTreatmentPlan->examination_id }}>
                    <input type="hidden" name="treatment_plan_id" id="treatment_plan_id" value={{ $patientTreatmentPlan->id }}>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="patient">@lang('Select Patient')</label>
                                    <select name="patient_disabled" id="patient" class="form-control select2" disabled>
                                        <option value="" disabled>Select Procedure</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}" {{ $patientTreatmentPlan->patient_id == $patient->id ? 'selected' : '' }}>
                                                {{ $patient->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="patient_id" value="{{ $patientTreatmentPlan->patient_id }}">
                                    @error('patient')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="examination">@lang('Teeth Examination Number')</label>
                                    <select name="examination_disabled" id="examination" class="form-control select2" disabled>
                                        <option value="" disabled>Select Procedure</option>
                                        @foreach ($teethProcedures as $procedure)
                                            <option value="{{ $procedure->id }}" {{ $patientTreatmentPlan->examination_id == $procedure->id ? 'selected' : '' }}>
                                                {{ $procedure->examination_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="examination_id" value="{{ $patientTreatmentPlan->examination_id }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="doctor">@lang('Select Doctor')</label>
                                    <select name="doctor_disabled" id="doctor" class="form-control select2" disabled>
                                        <option value="" disabled>Select Procedure</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ $patientTreatmentPlan->doctor_id == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="doctor_id" value="{{ $patientTreatmentPlan->doctor_id }}">
                                    @error('doctor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="comments">@lang('Comments')</label>
                                    <input name="comments" class="form-control" value="{{ $patientTreatmentPlan->comments }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="status">@lang('Status')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                        </div>
                                        <select
                                            class="form-control select2 ambitious-form-loading @error('status') is-invalid @enderror"
                                            required name="status" id="status">
                                            <option value="1" @if (old('status', $patientTreatmentPlan->status) == '1') selected @endif>
                                                @lang('Active')</option>
                                            <option value="1" @if (old('status', $patientTreatmentPlan->status) == '0') selected @endif>
                                                @lang('Inactive')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                            <div class="col-md-6">
                                    <input type="submit" value="{{ __('Submit') }}"
                                        class="btn btn-outline btn-info btn-lg" />
                                        <a href="{{ route('patient-treatment-plans.index') }}"
                                        class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>

                                </div>
                                <div class="col-md-6  text-md-right">
                                    <a href="{{ route('patient-treatment-plans.show', ['patient_treatment_plan' => $patientTreatmentPlan->id, 'print' => 'all']) }}"
                                        class="float-end btn-print btn btn-outline btn-secondary mt-2">Print All Plan</a>

                                    <a href="{{ route('patient-treatment-plans.show', ['patient_treatment_plan' => $patientTreatmentPlan->id, 'print' => 'ready']) }}"
                                    class="float-end btn-print btn btn-outline btn-secondary mt-2">Print Ready to Procedure</a>

                                    @if ($nonInvoicedProceduresCount > 0)
                                    <a href="#" id="generate-invoice" class="float-end btn-print btn btn-outline btn-secondary mt-2">Generate Invoice</a>
                                    @endif


                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@foreach ($teeth as $tooth)
<div class="row tooth-row-{{ $tooth->tooth_number }}">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">{{ $tooth->tooth_number }}</h3>
            </div>
            <div class="card-body">
                <p class="tooth-issues">
                    @foreach ($tooth->toothIssues as $issue)
                    <div class="alert alert-light" style="display: inline-block; margin-right: 30px;">
                        <h5>{{ $issue->tooth_issue }}</h5>
                        {{ $issue->description }}
                    </div>
                    @endforeach
                </p>
                <div class="table-responsive">
                    <table class="table table-hover border tooth-treatment">
                        <thead class="table-secondary">
                            <tr>
                                <th class="border">Procedure Category</th>
                                <th class="border">Procedure</th>
                                <th class="border">Cost</th>
                                <th class="border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($patientTreatmentPlanProcedures->where('tooth_number', $tooth->tooth_number)->isNotEmpty())

                            @foreach ($patientTreatmentPlanProcedures->where('tooth_number', $tooth->tooth_number) as $planProcedure)
                            @php
                            $isDisabled = $invoiceItems->where('patient_treatment_plan_procedure_id', $planProcedure->id)->isNotEmpty();
                            @endphp
                            <tr class="treatment-plan-inner-row">
                                <td class="border col-xl-3">
                                    <div class="input-group mb-3">
                                        <select required name="procedure_category[]"
                                            class="form-control choose-treatment-category @error('procedure') is-invalid @enderror"
                                            id="procedure_category" {{ ($isDisabled || $planProcedure->is_procedure_started === 'yes' ) ? 'disabled' : '' }}>
                                            <option value="" disabled selected>Select Procedure Category</option>
                                            @foreach ($procedureCategories as $procedureCategory)
                                            <option value="{{ $procedureCategory->id }}"
                                                @if (old('procedure_category', $planProcedure->procedure->ddprocedurecategory->id) == $procedureCategory->id) selected @endif>
                                                {{ $procedureCategory->title }}</option>
                                            @endforeach
                                        </select>
                                        <span class="dropdown-wrapper" aria-hidden="true"></span>
                                    </div>
                                </td>
                                <td class="border col-xl-3">
                                    <div class="input-group mb-3">
                                        <select required name="procedure[]"
                                            class="form-control choose-treatment-plan @error('procedure') is-invalid @enderror"
                                            id="procedure" {{ ($isDisabled || $planProcedure->is_procedure_started === 'yes' ) ? 'disabled' : '' }}>
                                            <option value="" disabled selected>Select Procedure Category</option>
                                            @foreach ($procedures as $procedure)
                                            <option value="{{ $procedure->id }}"
                                                @if (old('procedure', $planProcedure->dd_procedure_id) == $procedure->id) selected @endif>
                                                {{ $procedure->title }}</option>
                                            @endforeach
                                        </select>
                                        <span class="dropdown-wrapper" aria-hidden="true"></span>
                                    </div>
                                </td>
                                <td class="border">
                                    <div class="input-group mb-3">
                                        <span class="cost">{{ $planProcedure->procedure->price }}</span>

                                    </div>
                                </td>
                                <td class="border ">

                                <div class="action-tab row">
                                <div class="treatment-plan-edit-action-tab-list col-xl-6 col-md-12 col-sm-12">
                                        <div>
                                            <input class="form-check-input check-input"
                                            {{ $planProcedure->status === 'activated' ? 'checked' : '' }}
                                            type="checkbox"
                                            {{ ($isDisabled || ($planProcedure->is_procedure_started === 'yes' && $planProcedure->status === 'activated')) ? 'disabled' : '' }}>
                                        <label class="form-check-label">
                                            Add to procedure
                                        </label>
                                        </div>
                                        <div>
                                            <input class="form-check-input check-input-start"
                                            {{ $planProcedure->is_procedure_started === 'yes' ? 'checked' : '' }}
                                            type="checkbox"
                                            {{ ($isDisabled || ($planProcedure->is_procedure_started === 'yes' && $planProcedure->status === 'activated')) ? 'disabled' : '' }}>
                                            <label class="form-check-label" style="">
                                                Start Procedure
                                            </label>
                                        </div>
                                        <div>
                                            <input class="form-check-input check-input-finished" {{ $planProcedure->is_procedure_finished === 'yes' ? 'checked' : '' }}  type="checkbox" {{ $isDisabled ? 'disabled' : '' }}>
                                            <label class="form-check-label" style="">
                                                Procedure Finished
                                            </label>
                                        </div>
                                    </div>

                                    <div class="action-tab-btn responsive-width col-xl-6 col-md-12 col-sm-12">
                                        <button type="button" class="btn btn-success m-save responsive-width-item" {{ $isDisabled ? 'disabled' : '' }}><i class="fas fa-save"></i></button>
                                        <button type="button" class="btn btn-danger m-remove responsive-width-item" {{ $isDisabled ? 'disabled' : '' }}><i class="fas fa-trash"></i></button>
                                        <button type="button" class="btn btn-info m-add responsive-width-item"><i class="fas fa-plus"></i></button>
                                        <input type="hidden" name="planProcedure" id="planProcedure" value="{{ $planProcedure->id }}">
                                    </div>
                                </div>
                                
                                </td>
                            </tr>
                            @endforeach
                            @else


                            <tr class="treatment-plan-inner-row">
                                <td  class="border col-xl-3">
                                    <div class="input-group mb-3">

                                        <select required name="procedure_category"
                                            class="form-control choose-treatment-category @error('procedure') is-invalid @enderror"
                                            id="procedure_category">
                                            <option value="" disabled selected>Select Procedure Category</option>
                                            @foreach ($procedureCategories as $procedureCategory)
                                                <option value="{{ $procedureCategory->id }}">{{ $procedureCategory->title }}</option>
                                            @endforeach
                                        </select>
                                        <span class="dropdown-wrapper" aria-hidden="true"></span>
                                    </div>
                                </td>
                                <td  class="border col-xl-3">
                                    <div class="input-group mb-3">

                                        <select required name="procedure"
                                            class="form-control choose-treatment-plan @error('procedure') is-invalid @enderror"
                                            id="procedure">
                                            <option value="" disabled selected>Select Procedure</option>
                                        </select>
                                        <span class="dropdown-wrapper" aria-hidden="true"></span>
                                    </div>
                                </td>
                                <td class="border">
                                    <div class="input-group mb-3">
                                        <span class="cost"></span>
                                    </div>
                                </td>
                                <td class="border ">
                                    <div class="action-tab row">
                                        <div class="treatment-plan-edit-action-tab-list col-xl-6 col-md-12 col-sm-12">
                                            <div>
                                                <input class="form-check-input" type="checkbox" value="activated">
                                                <label class="form-check-label">
                                                    Add to procedure
                                                </label>
                                            </div>
                                        </div>
                                        <div class="action-tab-btn responsive-width col-xl-6 col-md-12 col-sm-12">
                                            <button type="button" class="btn btn-success m-save responsive-width-item"><i class="fas fa-save"></i></button>
                                            <button type="button" class="btn btn-danger m-remove responsive-width-item"><i class="fas fa-trash"></i></button>
                                            <button type="button" class="btn btn-info m-add responsive-width-item"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    $(window).on('load', function() {
        var Id = $('#demo_value').val();
        console.log("This is Id:", Id);
        $('#examination_id').val(Id);
        $('#examination_id').trigger('change');


        let allTreatmentCategoryIsEmpty = true;
        $('.choose-treatment-category').each(function() {
            if ($(this).val()) {
                allTreatmentCategoryIsEmpty = false;
                return false; // Exit the .each() loop
            }
        });

        if(allTreatmentCategoryIsEmpty == true) {
            $('.btn-print').hide();
        }

    });

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
                var options = '<option value="" disabled selected>Select Procedure</option>';
                $.each(procedures, function(index, procedure) {
                    options += '<option value="' + procedure.id + '">' + procedure.pr_number + '</option>';
                });
                $('#examination_id').html(options);
            },
            error: function(xhr, status, error) {
                alert('Failed to fetch Teeth Procedures! Please try again.');
            }
        });
    });

    $('.container-fluid').on('change', '.choose-treatment-category', function() {
        var procedureCategory = $(this).val();
        var $currentRow = $(this).closest('.treatment-plan-inner-row'); // Find the closest row

        // Perform AJAX request
        $.ajax({
            url: '{{ route("fetch.procedure") }}',
            type: 'GET',
            data: { procedure_category: procedureCategory },
            success: function(data) {
                var ddprocedures = data.ddprocedures;
                var options = '<option value="" disabled selected>Select Any Procedure</option>';

                // Build options for the select in the current row
                $.each(ddprocedures, function(index, ddprocedure) {
                    options += '<option value="' + ddprocedure.id + '">' + ddprocedure.title + '</option>';
                });

                // Update the select element in the current row
                $currentRow.find('.choose-treatment-plan').html(options);
            },
            error: function() {
                alert('Failed to fetch procedures. Please try again.');
            }
        });
    });

    $('.container-fluid').on('change', '.choose-treatment-plan', function() {
        var treatmentPlanId = $(this).val();
        var $currentRow = $(this).closest('.treatment-plan-inner-row'); // Find the closest row

        // Perform AJAX request to get price and description
        $.ajax({
            url: '{{ route("fetch.treatmentDetails") }}',
            type: 'GET',
            data: { treatment_plan_id: treatmentPlanId },
            success: function(data) {
                console.log(data); // Log the response data for debugging

                if (data.treatmentPlan) {
                    var treatmentPlan = data.treatmentPlan;

                    // Debugging to ensure correct data and correct field selection
                    console.log('Cost:', treatmentPlan.price);
                    console.log('description:', treatmentPlan.description);

                    // Update the cost input and any other relevant fields in the current row
                    $currentRow.find('span.cost').text(treatmentPlan.price);
                } else {
                    console.error('treatmentPlan not found in response:', data);
                }
            },
            error: function() {
                alert('Failed to fetch treatment details. Please try again.');
            }
        });
    });

    $('#examination_id').on('change', function() {
        $('.tooth-row-0').show();
        var examinationId = $(this).val();
        $.ajax({
            url: '{{ route("fetch.teeth") }}',
            type: 'GET',
            data: { examination_id: examinationId },
            success: function(data) {
                var teeth = data.teeth;
                var doctor = data.doctorDetails;
                doctor_value = '<option value="' + doctor.id + '">' + doctor.name + '</option>';
                $('#doctor').html(doctor_value);

                var newRow = new Array();
                $.each(teeth, function(index, tooth) {

                    var toothString = JSON.stringify(tooth, null, 2); // The second parameter is a replacer function (optional), and the third parameter is the number of spaces to use for indentation (optional)

                        newRow.push($('.tooth-row-0').clone());


                        // Clear existing data in the cloned row
                        newRow[index].find('.card-title').text(tooth.tooth_number);


                        $.each(tooth, function(index_tooth, toothIssue) {
                            var toothString = JSON.stringify(toothIssue, null, 2); // The second parameter is a replacer function (optional), and the third parameter is the number of spaces to use for indentation (optional)

                        });

                        $.each(tooth.tooth_issues, function(index_tooth, toothIssue) {

                            var issuetoothString = JSON.stringify(toothIssue, null, 2); // The second parameter is a replacer function (optional), and the third parameter is the number of spaces to use for indentation (optional)


                            newRow[index].find('p.tooth-issues').append('<div class="alert alert-light" style="    display: inline-block;margin-right: 30px;"><h5>'+toothIssue.tooth_issue+'</h5> '+toothIssue.description+'</div>');
                        });
                        newRow[index].find('table.tooth-issues tbody tr').first().remove();




                });
                $('.treatment-plan-row').after(newRow);
                $('.tooth-row-0').last().remove();

            },
            error: function(xhr, status, error) {
                alert('Failed to fetch Teeth Procedures! Please try again.');
            }
        });
    });

    $('.card').on("click", ".m-add", function () {
        // Find the closest .treatment-plan-inner-row to the clicked element
        var $row = $(this).closest('.treatment-plan-inner-row');
        // Clone the first row of the table within the same scope as $row
        let rowTreatmentPlan = $row.closest(".table.tooth-treatment").find("tbody tr:first").clone();

        // Reset the values of the cloned row's fields
        rowTreatmentPlan.find('select').val('');
        rowTreatmentPlan.find('.cost').text('');
        rowTreatmentPlan.find('input[type="checkbox"]').prop('checked', false);
        rowTreatmentPlan.find('input[type="checkbox"]').prop('disabled', false);
        rowTreatmentPlan.find('select').prop('disabled', false);
        rowTreatmentPlan.find('button').prop('disabled', false);
        rowTreatmentPlan.find('input[type="hidden"]').val('');  // Clear the hidden field

        rowTreatmentPlan.find('.check-input-start').closest('label').hide();
        rowTreatmentPlan.find('.check-input-finished').closest('label').hide();


        // Append the cloned and reset row content to the tbody
        $row.closest(".table.tooth-treatment").find("tbody").append('<tr class="treatment-plan-inner-row">' + rowTreatmentPlan.html() + '</tr>');

        // Clear the values of the appended row explicitly
        let $newRow = $row.closest(".table.tooth-treatment").find("tbody tr:last");
        $newRow.find('select').val('');
        $newRow.find('.cost').text('');
        $newRow.find('input[type="checkbox"]').prop('checked', false);
        $newRow.find('input[type="checkbox"]').prop('disabled', false);
        $newRow.find('select').prop('disabled', false);
        $newRow.find('button').prop('disabled', false);
    });

    $(document).on("click", ".m-save", function () {

        var $row = $(this).closest('.treatment-plan-inner-row');
        var procedureCategory = $row.find('.choose-treatment-category').val();
        var procedure = $row.find('.choose-treatment-plan').val();
        var status = $row.find('.check-input').is(':checked') ? 'activated' : 'pending';
        var is_procedure_started = $row.find('.check-input-start').is(':checked') ? 'yes' : 'no';
        var is_procedure_finished = $row.find('.check-input-finished').is(':checked') ? 'yes' : 'no';
        var toothNumber = $row.closest('.card').find('.card-header.bg-info h3.card-title').text();
        console.log("Tooth Number: ",toothNumber)
        var treatmentPlanId = $('input[name="treatment_plan_id"]').val();
        console.log("This is Procedure performed:",procedure,"toothNumber is:",toothNumber,"treatmentPlanId is:",treatmentPlanId,"status:",status);
        if (!procedureCategory || !procedure) {
            alert('Either Category Or Procedure is not selected!');
            return;
        }
        $.ajax({
            url: '{{ route("patient-treatment-plan-procedures.store") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                treatment_plan_id: treatmentPlanId,
                tooth_number: toothNumber,
                procedure: procedure,
                status: status,
                is_procedure_started: is_procedure_started,
                is_procedure_finished: is_procedure_finished,
            },
            success: function(response) {
                alert('Record saved successfully.');
                // You can add more logic here to update the UI based on the response
                $('.btn-print').show();
            },
            error: function(xhr, status, error) {
                // Parse the JSON response
                var response = JSON.parse(xhr.responseText);
                if (xhr.status === 403) {
                    alert(response.message);
                } else {
                    alert('Failed to save the record! Please try again.');
                }
            }
        });

    });


    $(document).on("click", ".m-remove", function () {
        var $row = $(this).closest('.treatment-plan-inner-row');
        var planProcedureId = $row.find('input[name="planProcedure"]').val(); // Get planProcedure ID

        if ($(this).closest('tbody').find('tr').length == 1) {
            alert('You can\'t delete this record!');
            return 0;
        }

        $.ajax({
            url: '{{ url("patient-treatment-plan-procedures") }}/' + planProcedureId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE',
            },
            success: function(response) {
                alert('Deleted successfully.');
                // Optionally remove the row from the DOM or refresh the table
                $row.remove();
            },
            error: function(xhr, status, error) {
                if (xhr.status === 403) {
                    alert('Record cannot be deleted as it is associated with an invoice item.');
                } else if (xhr.status === 404) {
                    alert('Record not found!');
                } else {
                    alert('Failed to delete the record! Please try again.');
                }
            }
        });
    });


    $('#generate-invoice').click(function(e) {
        e.preventDefault();

        // Get necessary data
        var treatmentPlanId = $('[name="treatment_plan_id"]').val();

        // Redirect to the invoice creation route with treatment_plan_id
        var url = "{{ route('invoices.create') }}";
        url += "?treatment_plan_id=" + treatmentPlanId;

        window.location.href = url;
    });

    $(document).ready(function() {
        $('.check-input').each(function() {
            var $row = $(this).closest('.treatment-plan-inner-row');
            if ($(this).is(':checked')) {
                $row.find('.check-input-start').closest('label').show();
            } else {
                $row.find('.check-input-start').closest('label').hide();
            }
        });

        $('.check-input-start').each(function() {
            var $row = $(this).closest('.treatment-plan-inner-row');
            if ($(this).is(':checked')) {
                $row.find('.check-input-finished').closest('label').show();
            } else {
                $row.find('.check-input-finished').closest('label').hide();
            }
        });
    });

});
</script>



@endsection

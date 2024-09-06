<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .size{
            font-size: 14px;
        }
        @media print {

            /* Hide elements that should not be printed */
            .no-print,
            #doPrint {
                display: none;
            }

            /* Ensure the card and content styles are maintained */
            .card {
                border: 1px solid #dee2e6;
                border-radius: 0.25rem;
                padding: 1rem;
            }

            .card-header bg-info,
            .card-body {
                padding: 0;
            }

            /* Make sure the layout fits well on the page */
            .container-fluid,
            .row,
            .col-md-3,
            .col-md-4,
            .col-md-12 {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .profile-user-img {
                width: 50px;
                height: 50px;
                object-fit: cover;
            }

            /* Adjust font sizes for printing */
            body {
                font-size: 12pt;
            }

            .btn {
                display: none;
            }
        }
    </style>
</head>
@extends('layouts.layout')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('patient-appointments.index') }}" class="btn btn-outline btn-info"><i
                                class="fas fa-eye"></i> @lang('View All')</a>

                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-appointments.index') }}">@lang('Customer Appointment')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Customer Appointment Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Customer Appointment Info')</h3>
                </div>
                <div class="card-body ">
                    <div class="border p-3">
                        <div class="row ">
                            <div class="col-md-12">
                                <h4 class="pb-3"><img src="{{ asset('assets/images/logo.png') }}" alt="{{ $ApplicationSetting->item_name }}"
                                    id="custom-opacity-sidebar" class="brand-image">Glow & Grow Salon</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="size">From</h6>
                            </div>
                            <div class="col-md-4">
                                <h6 class="size">To</h6>
                            </div>
                            <div class="col-md-4">
                                <h6 class="size">#{{ $patientAppointment->id }}</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="size">Glow & Grow Salon</h6>
                            </div>
                            <div class="col-md-4">
                                <h6 class="size">{{ $patientAppointment->user->name ?? ' ' }}</h6>
                            </div>
                            <div class="col-md-4">
                                <h6 class="size">Date: {{ $patientAppointment->appointment_date }}</h6>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <h6 class="size">Islamabad,Pakistan</h6>
                            </div>
                            <div class="col-md-4">
                                <h6 class="size">{{ $patientAppointment->user->address }}</h6>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <h6 class="size">Email: info@jantrah.com</h6>
                            </div>
                            <div class="col-md-4">
                                <h6 class="size">Email: {{ $patientAppointment->user->email }}</h6>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <h6 class="size">Phone: 051234322</h6>
                            </div>
                            <div class="col-md-4">
                                <h6 class="size">Phone: {{ $patientAppointment->user->phone }}</h6>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <hr class="pb-3">



                        <div class="row">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        @if ($profilePicture)
                                            <img class="profile-user-img img-fluid img-circle"
                                                src="{{ asset('storage/' . $profilePicture) }}" alt="Profile Picture"
                                                style="width: 50px; height: 50px; object-fit: cover;" />
                                        @else
                                            <img class="profile-user-img img-fluid rounded-circle"
                                                src="{{ asset('assets/images/profile/male.png') }}"
                                                alt="Default Profile Picture"
                                                style="width: 50px; height: 50px; object-fit: cover;" />
                                        @endif
                                    </div>
                                    <div>
                                        <label for="user_id">@lang('Customer Name')</label>
                                        <p>{{ $patientAppointment->user->name ?? ' ' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="appointment_number">@lang('CR Number')</label>
                                    <p>{{ $patientAppointment->user->patientdetails->mrn_number ?? ' ' }}</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="appointment_number">@lang('Contact Number')</label>
                                    <p>{{ $patientAppointment->user->phone ?? ' ' }}</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        @if ($profilePicture)
                                            <img class="profile-user-img img-fluid img-circle"
                                                src="{{ asset('storage/' . $patientAppointment->user->profilePicture) }}"
                                                alt="Profile Picture"
                                                style="width: 50px; height: 50px; object-fit: cover;" />
                                        @else
                                            <img class="profile-user-img img-fluid rounded-circle"
                                                src="{{ asset('assets/images/profile/male.png') }}"
                                                alt="Default Profile Picture"
                                                style="width: 50px; height: 50px; object-fit: cover;" />
                                        @endif
                                    </div>
                                    <div>
                                        <label for="doctor_id">@lang('Employee Name')</label>
                                        <p>{{ $patientAppointment->doctor->name ?? ' ' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="appointment_number">@lang('Appointment Number')</label>
                                    <p>{{ $patientAppointment->appointment_number ?? ' ' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="appointment_date">@lang('Appointment Date')</label>
                                    <p>{{ \Carbon\Carbon::parse($patientAppointment->appointment_date)->format('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="appointment_slot">@lang('Appointment Time')</label>
                                    <p>{{ \Carbon\Carbon::parse($patientAppointment->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($patientAppointment->end_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    <p>{{ $patientAppointment->appointmentstatus->name ?? ' ' }}</p>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="problem">@lang('Problem/Description')</label>
                                    <p>{{ $patientAppointment->problem }}</p>
                                </div>
                            </div>


                        </div>

                        <div class="row mt-4">


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    @php
                                        $currentDate = \Carbon\Carbon::now();
                                        $currentStatus = $statuses->firstWhere(
                                            'id',
                                            $patientAppointment->appointment_status_id,
                                        );

                                        $isDisabled =
                                            isset($currentStatus) &&
                                            ($currentStatus->name == 'Cancelled' ||
                                                $currentStatus->name == 'Processed' ||
                                                $patientAppointment->created_at < $currentDate);
                                    @endphp


                                    <select id="status" name="status" class="form-control"
                                        @if ($isDisabled) disabled @endif>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}"
                                                @if (old('status', $patientAppointment->appointment_status_id) == $status->id) selected @endif>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8"></div>
                            <div class="col-md-2">
                                <div class="btn pt-4">
                                    <button id="doPrint" class="btn btn-default"><i class="fas fa-print"></i>
                                        Print</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-2 no-print">
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
            $('#status').on('change', function() {
                var statusId = $(this).val();
                var appointmentId = {{ $patientAppointment->id }};

                if (statusId == '4' || statusId === '3') {
                    var confirmationMessage = statusId === '2' ?
                        'Are you sure you want to Cancel the appointment?' :
                        'Are you sure you want to Processed the appointment?';

                    var userConfirmed = confirm(confirmationMessage);
                    if (userConfirmed) {
                        // Disabling the status select
                        $(this).prop('disabled', true);
                        localStorage.setItem('alertShown', 'true');

                        // Sending the AJAX request
                        $.ajax({
                            url: '{{ route('ajax.example') }}', // Update this route to point to your actual route for updating the status
                            type: 'POST', // Changed to POST for updating data
                            data: {
                                statusId: statusId,
                                appointmentId: appointmentId,
                                _token: '{{ csrf_token() }}' // Include CSRF token for security
                            },
                            success: function(response) {
                                alert("Status updated successfully");
                                // Optionally reload the page if needed
                                // window.location.reload();
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                            }
                        });
                    } else {
                        // Reset the status select to its previous value if the user cancels
                        $(this).val($(this).data('previous'));
                    }
                }
                // Save the current value for future reference
                $(this).data('previous', statusId);
            });
        });
    </script>




    @include('layouts.delete_modal')
@endsection

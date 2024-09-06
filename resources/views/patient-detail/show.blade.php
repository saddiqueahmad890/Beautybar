@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-details.index') }}">@lang('Customer')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Customer Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info d-flex">
                    <div class="profile-pic2">
                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/' . $profilePicture) }}"
                            alt="" />
                    </div>
                    <h3 class="card-title ml-2 mt-2">@lang('Customer Info') - {{ $patientDetail->name }}</h3>
                    @can('patient-detail-update')
                        <div class="card-tools" style="position: absolute;right: 28px;">
                            <a href="{{ route('patient-details.edit', $patientDetail) }}"
                                class="btn btn-info">@lang('Edit')</a>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row bg-custom p-2">
                        <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="name">@lang('Name')</label>
                                <p>{{ $patientDetail->name }}</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="cnic">@lang('CR Number')</label>
                                <p>{!! $patientDetail->patientDetails->mrn_number !!}</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="phone">@lang('Phone')</label>
                                <p>{{ $patientDetail->phone }}</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="email">@lang('Email')</label>
                                <p>{{ $patientDetail->email }}</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="gender">@lang('Gender')</label>
                                <p>{{ ucfirst($patientDetail->gender) }}</p>
                            </div>
                        </div>

                        <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="status">@lang('Status')</label>
                                <p>
                                    @if ($patientDetail->status == 1)
                                        <span class="badge badge-pill badge-success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="credit_balance">@lang('Credit Balance')</label>
                                <p>{!! $patientDetail->patientDetails->credit_balance !!}</p>
                            </div>
                        </div> --}}


                        <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="area">@lang('Area')</label>
                                <p>{!! $patientDetail->patientDetails->area !!}</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="city">@lang('City')</label>
                                <p>{!! $patientDetail->patientDetails->city !!}</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="address">@lang('Address')</label>
                                <p>{{ $patientDetail->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <div class="card">

        <div class="card-header bg-info">
            Related Modules
        </div>
        <div class="card-body">
            <!-- Nav tabs -->


            <!-- Tab content -->

            <div class="" id="tab-invoices" role="tabpanel" aria-labelledby="settings-tab">
                <h3>Invoices</h3>
                <table class="table table-striped" id="laravel_datatable">
                    <thead>
                        <tr>
                            <th>@lang('Invoice Number')</th>

                            {{-- <th>@lang('Service')</th> --}}
                            <th>@lang('Total')</th>
                            <th>@lang('Paid')</th>
                            <th>@lang('Due')</th>
                            <th>@lang('Dated')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    <a href="{{ route('invoices.show', $invoice['id']) }}"
                                        class="text-decoration-underline">
                                        {{ $invoice->invoice_number }}
                                    </a>



                                    {{-- <td>{{ isset($invoice->patienttreatmentplan->treatment_plan_number) ? $invoice->patienttreatmentplan->treatment_plan_number : '-' }} --}}
                                </td>
                                <td>{{ isset($invoice->grand_total) ? $invoice->grand_total : '-' }}</td>
                                <td>{{ $invoice->paid }}</td>
                                <td>{{ $invoice->due }}</td>
                                <td>{{ $invoice->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


        </div>

    </div>
@endsection

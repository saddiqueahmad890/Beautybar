@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">@lang('Report')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Report')</h3>
                    <div class="card-tools">
                        <a class="btn btn-primary" target="_blank" href="{{ route('new-reports.index') }}?export=1">
                            <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                        </a>
                        <button class="btn btn-default" data-toggle="collapse" href="#filter">
                            <i class="fas fa-filter"></i> @lang('Filter')
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                    <form class="border col-12 m-0 p-0 mb-2" action="" method="get">
                    <input type="hidden" name="isFilterActive" value="true">
                        <div class="row col-12 p-0 m-0">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>@lang('Invoice #')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        </div>
                                        <input type="text" name="invoice_number" class="form-control"
                                            value="{{ old('invoice_number', request()->invoice_number) }}"
                                            placeholder="@lang('Invoice Number')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>@lang('CR NO#')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        </div>
                                        <input type="text" name="mrn_number" class="form-control"
                                        value="{{ old('mrn_number', request()->mrn_number) }}"
                                        placeholder="@lang('CR NO#')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>@lang('Customer')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" name="patient" class="form-control"
                                            value="{{ old('patient', request()->patient) }}" placeholder="@lang('Customer')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>@lang('Employee')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" name="doctor" class="form-control"
                                            value="{{ old('doctor', request()->doctor) }}" placeholder="@lang('Employee')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>@lang('Date From')</label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="text" name="date_from" id="date_from" class="form-control flatpickr flatpickr-input"
                                            placeholder="@lang('Date From')"
                                            value="{{ old('date_from', request()->date_from) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>@lang('Date To')</label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="text" name="date_to" id="date_to" class="form-control flatpickr"
                                            placeholder="@lang('Date To')"
                                            value="{{ old('date_to', request()->date_to) }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row my-3 col-12 p-0 m-0">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                @if (request()->isFilterActive)
                                <a href="{{ route('new-reports.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                @endif                                
                            </div>
                        </div>
                    </form>
                </div>
                    <div class="row col-12 p-0 m-0">
                <div class="table-responsive bg-light col-12 p-0" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-bordered custom-table m-0">
                        <thead>
                            <tr>
                                <th>@lang('Invoice #')</th>
                                <th>@lang('CR NO#')</th>
                                <th>@lang('Customer')</th>
                                <th>@lang('Employee')</th>
                                <th>@lang('Service Category')</th>
                                <th>@lang('Service')</th>
                                <th>@lang('Total Amount')</th>
                                <th>@lang('Saloon Amount')</th>
                                <th>@lang('Commission %')</th>
                                <th>@lang('Total Commission Value')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $groupKey => $groupedInvoices)
                                @php
                                    $invoice = $groupedInvoices->first();
                                    $totalAmount = $groupedInvoices->sum('grand_total');
                                @endphp
                                @foreach ($invoice->invoiceItems as $item)
                                    @php
                                        $commissionPercentage = $item->doctor->doctorDetails->commission ?? 0;
                                        $commissionAmount = $item->price * ($commissionPercentage / 100);
                                        $saloonAmount = $item->price - $commissionAmount;
                                    @endphp
                                    <tr>
                                        @if ($loop->first)
                                        <td rowspan="{{ $invoice->invoiceItems->count() }}">
                                            {{ $invoice->invoice_number }}</td>
                                            <td rowspan="{{ $invoice->invoiceItems->count() }}">
                                                {{ $invoice->user->patientDetails->mrn_number ?? '-' }}</td>
                                            <td rowspan="{{ $invoice->invoiceItems->count() }}">
                                                {{ $invoice->user->name ?? '' }}
                                            </td>
                                        @endif
                                        <td>{{ $item->doctor->name ?? '-' }}</td>
                                        <td>{{ $item->ddprocedure->ddprocedurecategory->title ?? '-' }}</td>
                                        <td>{{ $item->ddprocedure->title ?? '-' }}</td>
                                        <td>{{ $item->price ?? '-' }}</td>
                                        <td>{{ $saloonAmount ?? '-' }}</td>
                                        <td>{{ $commissionPercentage ?? '-' }}</td>
                                        <td>{{ $commissionAmount ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr class="text-center">
                                    <td colspan="10" class="text-secondary">@lang('Apply Filter for Report')</td>
                                </tr>
                            @endforelse

                            @if ($invoices->count())
                                @php
                                    $grandTotal = $invoices->sum(function ($groupedInvoices) {
                                        return $groupedInvoices->sum('grand_total');
                                    });
                                    $totalCommission = $invoices->sum(function ($groupedInvoices) {
                                        return $groupedInvoices->sum(function ($invoice) {
                                            return $invoice->invoiceItems->sum(function ($item) {
                                                $commissionPercentage = $item->doctor->doctorDetails->commission ?? 0;
                                                return $item->price * ($commissionPercentage / 100);
                                            });
                                        });
                                    });
                                    $totalSaloonAmount = $grandTotal - $totalCommission;
                                @endphp
                                <tr>
                                    <td colspan="5"></td>
                                    <th>@lang('Total')</th>
                                    <th>{{ $grandTotal }}</th>
                                    <th>{{ $totalSaloonAmount }}</th>
                                    <th>-</th>
                                    <th>{{ $totalCommission }}</th>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>


    </div>
    </div>
    </div>
    </div>
@endsection

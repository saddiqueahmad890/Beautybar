@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @can('invoice-create')
                        <h3>
                            <a href="{{ route('invoices.create', ['type' => request()->query('type')]) }}"
                                class="btn btn-outline btn-info">+ @lang('Invoice')</a>
                            <span class="pull-right"></span>
                        </h3>
                    @endcan
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">@lang('Services Invoice')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Services Invoice List')</h3>
                    <div class="card-tools">
                        <button class="btn btn-default" data-toggle="collapse" href="#filter">
                            <i class="fas fa-filter"></i> @lang('Filter')
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="{{ route('invoice.index') }}" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="type" value="{{ request()->query('type') }}">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Invoice #')</label>
                                            <input type="text" name="invoice_number" id="invoice_number"
                                                class="form-control" placeholder="@lang('Invoice Number')"
                                                value="{{ old('invoice_number', request()->invoice_number) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Customer')</label>
                                            <select name="user_id" class="form-control select2" id="user_id">
                                                <option value="">--@lang('Select')--</option>
                                                @foreach ($patients as $patient)
                                                    <option value="{{ $patient->id }}"
                                                        {{ old('user_id', request()->user_id) == $patient->id ? 'selected' : '' }}>
                                                        {{ $patient->name }}
                                                    </option>
                                                @endforeach
                                            </select>
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
                                                    value="{{ old('doctor', request()->doctor) }}"
                                                    placeholder="@lang('Employee')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Invoice Date')</label>
                                            <input type="text" name="invoice_date" id="invoice_date"
                                                class="form-control flatpickr" placeholder="@lang('Invoice Date')"
                                                value="{{ old('invoice_date', request()->invoice_date) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('invoice.index') }}"
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
                                <th>@lang('Invoice #')</th>

                                <th>@lang('Customer')</th>
                                <th>@lang('Employee')</th>
                                <th>@lang('Category')</th>
                                <th>@lang('Services')</th>

                                <th>@lang('Sub Total')</th>
                                <th>@lang('Grand Total')</th>
                                <th>@lang('Paid')</th>
                                <th>@lang('Due')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td><span style="text-wrap:nowrap;">{{ $invoice['invoice_number'] ?? '-' }}</span></td>
                                    <td>{{ $invoice['user_name'] ?? ' ' }}</td>
                                    <td>
                                        @foreach ($invoice['items'] as $item)
                                            {{ $item->doctor->name ?? '' }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($invoice['items'] as $item)
                                            {{ $item->ddprocedure->ddprocedurecategory->title ?? ' ' }}<br>
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach ($invoice['items'] as $item)
                                            {{ $item->ddprocedure->title ?? ' ' }}<br>
                                        @endforeach

                                    </td>

                                    <td>
                                        @foreach ($invoice['items'] as $item)
                                            {{ $item->total }}<br>
                                        @endforeach
                                    </td>
                                    <td>{{ $invoice['grand_total'] }}</td>
                                    <td>{{ $invoice['paid'] }}</td>
                                    <td>{{ $invoice['due'] }}</td>
                                    <td>{{ date($companySettings->date_format ?? 'Y-m-d', strtotime($invoice['invoice_date'])) }}
                                    </td>
                                    <td class="responsive-width">
                                        <a href="{{ route('invoices.show', $invoice['id']) }}?type={{ request()->query('type') }}"
                                            class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="tooltip" title="@lang('View')">
                                            <i class="fa fa-eye ambitious-padding-btn"></i>
                                        </a>

                                        @can('invoice-update')
                                            <a href="{{ route('invoices.edit', $invoice['id']) }}?type={{ request()->query('type') }}"
                                                class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="tooltip" title="@lang('Edit')">
                                                <i class="fa fa-edit ambitious-padding-btn"></i>
                                            </a>
                                        @endcan

                                        @can('invoice-delete')
                                            <a href="#" data-href="{{ route('invoices.destroy', $invoice['id']) }}"
                                                class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i
                                                    class="fa fa-trash ambitious-padding-btn"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $invoices->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.delete_modal')
@endsection

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
                        <li class="breadcrumb-item active">@lang('Sale Invoice')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Sale Invoice List')</h3>
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
                                    <div class="col-sm-4">
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
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Invoice Number')</label>
                                            <input type="text" name="invoice_number" id="invoice_number"
                                                class="form-control" placeholder="@lang('Invoice Number')"
                                                value="{{ old('invoice_number', request()->invoice_number) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
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
                                            <a href="{{ route('invoice.index', ['type' => request()->query('type')]) }}"
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
                                <th>@lang('items')</th>
                                <th>@lang('Unit Sale Price')</th>
                                <th>@lang('Receptionist Qty')</th>
                                <th>@lang('Sold Qty')</th>
                                <th>@lang('Stock Quantity')</th>
                                <th>@lang('Paid')</th>
                                <th>@lang('Grand Total')</th>
                                <th>@lang('Invoice Date')</th>
                                <th>@lang('Approval Date')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                //    echo '<pre>'; var_dump($paginatedInvoices);exit();
                            @endphp
                            @foreach ($paginatedInvoices as $invoice)
                                <tr>
                                    <!-- Invoice Details -->
                                    <td><span style="white-space: nowrap;">{{ $invoice['invoice_number'] ?? '-' }}</span>
                                    </td>
                                    <td>{{ $invoice['user_name'] ?? ' ' }}</td>
                                    @forelse ($invoice['items'] as $item)
                                        @php
                                            $inventory = \App\Models\Inventory::where(
                                                'id',
                                                $item->inventory_id,
                                            )->first();
                                            $itemTitle = $inventory->item->title ?? 'N/A';
                                            $itemsale = $inventory->unit_sale ?? 'N/A';
                                            $quantity = $inventory->quantity ?? 'N/A';
                                        @endphp
                                        <td>{{ $itemTitle }}</td>
                                        <td>{{ $itemsale }}</td>
                                    @empty
                                        <td colspan="4">@lang('No items found')</td>
                                    @endforelse
                                    <td>
                                        @foreach ($invoice['items'] as $item)
                                            {{ $item->quantity }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($invoice['items'] as $item)
                                            {{ $item->approved_quantity }}<br>
                                        @endforeach
                                    </td>
                                    <td>{{ $quantity }}</td>
                                    <td>{{ $invoice['paid'] }}</td>
                                    <td>{{ $invoice['grand_total'] }}</td>
                                    <td>
                                        @if ($invoice['invoice_date'])
                                            {{ date('j-M-y (h:i a)', strtotime($invoice['invoice_date'])) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($invoice['approval_date'])
                                            {{ date('j-M-y (h:i a)', strtotime($invoice['approval_date'])) }}
                                        @endif
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

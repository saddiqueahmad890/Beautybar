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
                            <a
                                href="{{ route('invoice.index', ['type' => request()->query('type')]) }}">@lang('Sale Invoice')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Sale Invoice Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Sale Invoice Info')</h3>
                    <div class="card-tools">
                        
                        <button id="doPrint" class="btn btn-default"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>
                <div id="print-area" class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="invoice p-3 mb-3">
                                <div class="row">
                                    <div class="col-12">
                                        <h4>
                                            <a href="{{ route('dashboard') }}" class="brand-link sidebar-light-info">
                                                <img src="{{ asset('assets/images/logo.png') }}"
                                                    alt="{{ $ApplicationSetting->item_name }}" id="custom-opacity-sidebar"
                                                    class="brand-image">
                                                <span
                                                    class="brand-text font-weight-light">{{ $ApplicationSetting->item_name }}</span>
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                                <div class="row invoice-info">
                                    <div class="col-sm-4 invoice-col">
                                        @lang('From')
                                        <address>
                                            <strong>{{ $ApplicationSetting->item_name }}</strong><br>
                                            @if ($ApplicationSetting->company_address)
                                                {!! str_replace(['script'], ['noscript'], $company->company_address) !!}
                                            @endif
                                            @lang('Email'): {{ $ApplicationSetting->company_email }}<br>
                                            @if ($ApplicationSetting->company_phone)
                                                @lang('Phone'): {{ $ApplicationSetting->company_phone }}
                                            @endif
                                        </address>
                                    </div>
                                    <div class="col-sm-4 invoice-col">
                                        @lang('To')
                                        <address>
                                            <strong>{{ $invoice->user->name }}</strong><br />
                                            @if ($invoice->user->address)
                                                {!! nl2br(str_replace(['script'], ['noscript'], $invoice->user->address)) !!}<br>
                                            @endif
                                            @lang('Email'): {{ $invoice->user->email }}<br>
                                            @if ($invoice->user->phone)
                                                @lang('Phone'): {{ $invoice->user->phone }}
                                            @endif
                                        </address>
                                    </div>
                                    <div class="col-sm-4 invoice-col">
                                        @lang('Invoice') #{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}<br>
                                        @lang('Date'):
                                        {{ date($ApplicationSetting->date_format ?? 'd-m-Y', strtotime($invoice->invoice_date)) }}<br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-striped custom-table">
                                            <thead>
                                                <tr>

                                                    <th scope="col" class="custom-th-width-20">@lang('items')

                                                    <th scope="col" class="custom-th-width-25">@lang('Description')</th>
                                                    <th scope="col" class="custom-th-width-20">@lang('Unit_sale')
                                                    <th scope="col" class="custom-th-width-25">@lang('Quantity')</th>

                                                    <th scope="col" class="custom-th-width-15">@lang('Sub Total')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoice->invoiceItems as $invoiceItem)
                                                    <tr>
                                                        <td>
                                                            @php
                                                                $inventory = \App\Models\Inventory::find(
                                                                    $invoiceItem->inventory_id,
                                                                );
                                                            @endphp
                                                            <span id="itemTitle">
                                                                {{ isset($inventory->item->title) ? $inventory->item->title : '' }}
                                                            </span>
                                                        <td> <span id="itemTitle">
                                                                {{ isset($inventory->description) ? $inventory->description : '' }}
                                                            </span>
                                                        <td><span id="itemTitle">
                                                                {{ isset($inventory->unit_sale) ? $inventory->unit_sale : '' }}
                                                            </span></td>
                                                        </td>
                                                        </td>
                                                        <td>{{ $invoiceItem->quantity }}</td>

                                                        <td>{{ $invoiceItem->total }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 offset-md-6">
                                        {{-- <p class="lead">@lang('Insurance'): {{ $invoice->insurance->name ?? 'N/A' }}</p> --}}
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>@lang('Total')</th>
                                                        <td>{{ $invoice->total }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>@lang('Paid')</th>
                                                        <td>{{ $invoice->paid }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>@lang('Due')</th>
                                                        <td>{{ $invoice->due }}</td>
                                                    </tr>
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

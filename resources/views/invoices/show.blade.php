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
                            <a href="{{ route('invoice.index',['type' => request()->query('type')]) }}">@lang('Services Invoice')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Services Invoice Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Services Invoice Info')</h3>
                    <div class="card-tools">
                        <a href="{{ route('invoices.edit', $invoice)  }}?type={{ request()->query('type') }}" class="btn btn-info">@lang('Edit')</a>
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
                                                <img src="{{ asset('https://blanchebeautybar.com/wp-content/uploads/2024/08/Web-Logo-1.png') }}"
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
                                            <strong>{{ $invoice->user->name??' '}}</strong><br />
                                            @if ($invoice->user->address ?? '')
                                                {!! nl2br(str_replace(['script'], ['noscript'], $invoice->user->address)) !!}<br>
                                            @endif
                                            @lang('Email'): {{ $invoice->user->email ?? ' ' }}<br>
                                            @if ($invoice->user->phone ??'')
                                                @lang('Phone'): {{ $invoice->user->phone ??' ' }}
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
                                                   
                                                        <th scope="col" class="custom-th-width-20">@lang('Services')
                                                        </th>
                                                   
                                                    <th scope="col" class="custom-th-width-25">@lang('Description')</th>

                                                    <th scope="col" class="custom-th-width-20">@lang('Price')</th>
                                                    <th scope="col" class="custom-th-width-15">@lang('Sub Total')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoice->invoiceItems as $invoiceItem)
                                                    <tr>
                                                       
                                                        
                                                            <td>{{ $invoiceItem->ddprocedure->title ?? ' '}}
                                                            <td>{{ $invoiceItem->ddprocedure->description ?? ' ' }}</td>

                                                      


                                                        </td>


                                                        <td>{{ $invoiceItem->price }}</td>
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
                                                        <th>@lang('Discount') {{ $invoice->discount_percentage }}</th>
                                                        <td>{{ $invoice->total_discount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>@lang('Vat') ({{ $invoice->vat_percentage }}%)</th>
                                                        <td>{{ $invoice->total_vat }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>@lang('Grand Total')</th>
                                                        <td>{{ $invoice->grand_total }}</td>
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

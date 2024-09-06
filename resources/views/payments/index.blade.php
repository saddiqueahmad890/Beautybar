@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- @can('payment-create') --}}
                        <h3><a href="{{ route('payments.create') }}" class="btn btn-outline btn-info">+ @lang('Add Expense')</a>
                            <span class="pull-right"></span>
                        </h3>
                    {{-- @endcan --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">@lang('Expense')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Expense List')</h3>
                    <div class="card-tools">
                        <a class="btn btn-primary" target="_blank" href="{{ route('payments.index') }}?export=1"><i class="fas fa-cloud-download-alt"></i> @lang('Export')</a>
                        <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if(request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    <div class="col-sm-3">
            <div class="form-group">
                <label>@lang('Payment mode')</label>
                <input type="text" id="account_name" name="account_name" value="{{ request()->account_name }}" class="form-control" placeholder="@lang('Payment mode')">
            </div>
        </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Title')</label>
                                            <input type="text" id="receiver_name" name="receiver_name" value="{{ request()->receiver_name }}" class="form-control" placeholder="@lang('Title')">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Start Date')</label>
                                            <input type="text" name="start_date" id="start_date" class="form-control flatpickr" placeholder="@lang('Start Date')" value="{{ old('start_date', request()->start_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('End Date')</label>
                                            <input type="text" name="end_date" id="end_date" class="form-control flatpickr" placeholder="@lang('End Date')" value="{{ old('end_date', request()->end_date) }}">
                                        </div>
                                    </div>
                                </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if(request()->isFilterActive)
                                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                
                                <th>@lang('Payment mode')</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Payment Date')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                   
                                    <td><span style="text-wrap:nowrap;">{{ $payment->account_name }}</span></td>
                                    <td>{{ $payment->receiver_name }}</td>
                                    <td>{{ $payment->amount }}</td>
                                    <td>{{ date($companySettings->date_format ?? 'Y-m-d', strtotime($payment->payment_date)) }}</td>
                                    <td class="responsive-width">
                                        <a href="{{ route('payments.show', $payment) }}" class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('View')"><i class="fa fa-eye ambitious-padding-btn"></i></a>
                                        @can('payment-update')
                                            <a href="{{ route('payments.edit', $payment) }}" class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>
                                        @endcan
                                        @can('payment-delete')
                                            <a href="#" data-href="{{ route('payments.destroy', $payment) }}" class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $payments->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@include('layouts.delete_modal')
@endsection

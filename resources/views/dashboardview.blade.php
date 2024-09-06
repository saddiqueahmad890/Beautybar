@extends('layouts.layout')

@section('one_page_css')
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('one_page_js')
    <!-- ChartJS -->
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>@lang('Dashboard')</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">@lang('Dashboard')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
<div class="d-flex justify-content-end">
    <button class="btn btn-default" data-toggle="collapse" href="#filter">
        <i class="fas fa-filter"></i> @lang('Filter')
    </button>
</div>

</div>
</div>
<div class="card-body">
    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
        <div class="card-body border">
            <form action="" method="get" role="form" autocomplete="off">
                <input type="hidden" name="isFilterActive" value="true">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date">@lang('Start Date')</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date">@lang('End Date')</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                     @if (request()->isFilterActive)
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">@lang('Clear')</a>
@endif

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

 
    <div class="row col-12 m-0 p-0">
        <!-- Patient Count Box -->
        <div class="col-md-4 col-sm-6 col-12 mt-3">
            <div class="info-box custom-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-user-injured"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text d-flex">
                        @lang('Customer: ')
                        <span class="info-box-number"> {{ $dashboardCounts['patients'] }}</span>
                    </span>
                    <a href='{{ route('patient-details.index') }}' class="text-decoration-underline">
                        @lang('View All')
                    </a>
                </div>
            </div>
        </div>

        <!-- Doctor Count Box -->
        <div class="col-md-4 col-sm-6 col-12 mt-3">
            <div class="info-box custom-box">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-user-md"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text d-flex">
                        @lang('Employee: ')
                        <span class="info-box-number"> {{ $dashboardCounts['doctors'] }}</span>
                    </span>
                    <a href='{{ route('doctor-details.index') }}' class="text-decoration-underline">
                        @lang('View All')
                    </a>
                </div>
            </div>
        </div>

        <!-- Invoice Count Box -->
        <div class="col-md-4 col-sm-6 col-12 mt-3">
            <div class="info-box custom-box">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-file-invoice-dollar"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">@lang('Invoice Count : ') {{ $dashboardCounts['invoices'] }}

                        <span class="info-box-text d-flex">
                            @lang('Total:')
                            <span class="info-box-number">{{ $dashboardCounts['total'] }}</span>
                        </span>
                        <span class="info-box-text d-flex">
                            @lang('Paid:')
                            <span class="info-box-number">{{ $dashboardCounts['paid'] }}</span>
                        </span>
                </div>
            </div>
        </div>

        <!-- Expense Box -->
        <div class="col-md-4 col-sm-6 col-12 mt-3">
            <div class="info-box custom-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-money-check"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">@lang('Purchased Inventory')</span>
                    <span class="info-box-number">Total Qty {{ $dashboardCounts['inventory'] }}</span>
                    <span class="info-box-text d-flex">
                         <a href='{{ route('inventories_purchased') }}' class="text-decoration-underline">
                        @lang('View All')
                         </a>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12 mt-3">
            <div class="info-box custom-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-money-check"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">@lang('Expense') <span class="" style="font-weight: bold"> {{ $dashboardCounts['payments'] }}</span></span>
                    <span class="info-box-text d-flex">
                        @lang('Total:')
                    <span class="info-box-number">{{ $dashboardCounts['totalAmount'] }}</span>
                    </span>
                      <a href='{{ route('payments.index') }}' class="text-decoration-underline">
                        @lang('View All')
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12 mt-3">
            <div class="info-box custom-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-money-check"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">@lang('Stock Inventory')</span>
                    <span class="info-box-number">Total Qty {{ $dashboardCounts['inventorystk'] }}</span>
                    <span class="info-box-text d-flex">
                       
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script src="{{ asset('assets/js/custom/dashboard/view.js') }}"></script>
@endpush

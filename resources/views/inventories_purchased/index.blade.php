@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Inventory Purchased')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h1 class="card-title">@lang('Inventory Purchased')</h1>
                    <div class="card-tools">
                        <a class="btn btn-primary" target="_blank"
                            href="{{ route('inventories_purchased') }}?export=1&{{ http_build_query(request()->query()) }}">
                            <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                        </a>
                        <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i>
                            @lang('Filter')</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                            <form class="border mb-3" method="GET" action="{{ route('inventories_purchased') }}">
                                <input type="hidden" name="isFilterActive" value="true">

                                <div class="row col-12 p-0 m-0">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Category')</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                </div>
                                                <select name="category_id" class="form-control">
                                                    <option value="">@lang('Select Category')</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="item_id">@lang('Item') <b class="text-danger">*</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-box"></i></span>
                                                </div>
                                                <select name="item_id" id="item_id" class="form-control">
                                                    <option value="">@lang('Select Item')</option>
                                                    @foreach ($items as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Start Date')</label>
                                            <div class="form-group input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input type="text" name="start_date" id="start_date"
                                                    class="form-control flatpickr" placeholder="@lang('Start Date')"
                                                    value="{{ old('start_date', request()->start_date) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('End Date')</label>
                                            <div class="form-group input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input type="text" name="end_date" id="end_date"
                                                    class="form-control flatpickr" placeholder="@lang('End Date')"
                                                    value="{{ old('end_date', request()->end_date) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row my-3 col-12 p-0 m-0">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('inventories_purchased') }}"
                                                class="btn btn-secondary">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="card-body">

                       <div style="overflow-y:auto; max-height:500px;">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>@lang('Supplier')</th>
                <th>@lang('Category')</th>
                <th>@lang('Item')</th>
                <th>@lang('Quantity')</th>
                <th>@lang('Purchased Quantity')</th>
                <th>@lang('Unit Cost')</th>
                <th>@lang('Unit Sale')</th>
                <th>@lang('Description')</th>
                <th>@lang('Purchased Time')</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQuantity = 0;
                $totalPurchasedQty = 0;
                $totalUnitCost = 0;
                $totalUnitSale = 0;
            @endphp

            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->supplier }}</td>
                    <td>{{ $log->category->title ?? ' ' }}</td>
                    <td>{{ $log->item->title ?? ' ' }}</td>
                    <td>{{ $log->quantity }}</td>
                    <td>{{ $log->purchased_qty }}</td>
                    <td>{{ $log->unit_cost }}</td>
                    <td>{{ $log->unit_sale }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->action_timestamp }}</td>
                </tr>

                @php
                    $totalQuantity += $log->quantity;
                    $totalPurchasedQty += $log->purchased_qty;
                    $totalUnitCost += $log->unit_cost;
                    $totalUnitSale += $log->unit_sale;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">@lang('Total')</th>
                <th>{{ $totalQuantity }}</th>
                <th>{{ $totalPurchasedQty }}</th>
                <th>{{ number_format($totalUnitCost, 2) }}</th>
                <th>{{ number_format($totalUnitSale, 2) }}</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
</div>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

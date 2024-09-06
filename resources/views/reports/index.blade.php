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
                        <li class="breadcrumb-item active">@lang('Stock Inventory Sold Report')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Stock Inventory Sold Report')</h3>
                    <div class="card-tools">
                        <a class="btn btn-primary" target="_blank"
                            href="{{ route('reports.stock-inventory-sold') }}?export=1&{{ http_build_query(request()->query()) }}">
                            <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                        </a>
                        <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i>
                            @lang('Filter')</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <form class="mb-3 border" action="{{ route('reports.stock-inventory-sold') }}" method="get">
                            <input type="hidden" name="isFilterActive" value="true">
                            <div class="row col-12 p-0 m-0">
                                <!-- Category Filter -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>@lang('Category')</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            </div>
                                            <select name="category_id" id="category_id" class="form-control">
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

                                <!-- Item Filter -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="item_id">@lang('Item')</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-box"></i></span>
                                            </div>
                                            <select id="item_id" name="item_id" class="form-control">
                                                <option value="">@lang('Select Item')</option>
                                                @if (request('category_id'))
                                                    @foreach ($items as $item)
                                                        @if ($item->category_id == request('category_id'))
                                                            <option value="{{ $item->id }}"
                                                                {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                                                {{ $item->title }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date From Filter -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>@lang('Date From')</label>
                                        <div class="form-group input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                            <input type="text" name="date_from" id="date_from"
                                                class="form-control flatpickr" placeholder="@lang('Date From')"
                                                value="{{ old('date_from', request()->date_from) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Date To Filter -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>@lang('Date To')</label>
                                        <div class="form-group input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                            <input type="text" name="date_to" id="date_to"
                                                class="form-control flatpickr" placeholder="@lang('Date To')"
                                                value="{{ old('date_to', request()->date_to) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit and Clear Buttons -->
                            <div class="row col-12 p-0 m-0 mb-3">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>

                                    @if (request()->isFilterActive)
                                        <a href="{{ route('reports.stock-inventory-sold') }}"
                                            class="btn btn-secondary">@lang('Clear')</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="row col-12 p-0 m-0 ">
                        <div class="card bg-custom col-12 m-0 p-0">

                            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                @php
                                    $totalSoldQuantity = 0;
                                    $totalUnitSalePrice = 0;
                                    $totalCost = 0;
                                @endphp

                                @forelse ($inventoryUsages as $usage)
                                    @php
                                        $totalSoldQuantity += $usage->sold_qty;
                                        $totalUnitSalePrice += $usage->unit_sale_price;
                                        $totalCost += $usage->sold_qty * $usage->unit_sale_price;
                                    @endphp
                                @empty
                                @endforelse

                                <table class="table table-bordered custom-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Category')</th>
                                            <th>@lang('Item')</th>
                                            <th>@lang('Sold Quantity') <br> Total {{ $totalSoldQuantity }}</th>
                                            <th>@lang('Unit Sale Price') <br> Total {{ $totalUnitSalePrice }} PKR</th>
                                            <th>@lang('Total Cost')<br>Total {{ $totalCost }} PKR</th>
                                            <th>@lang('Date')</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @forelse ($inventoryUsages as $usage)
                                            <tr>
                                                <td>{{ $usage->inventory->item->category->title ?? '-' }}</td>
                                                <td>{{ $usage->inventory->item->title ?? '-' }}</td>
                                                <td>{{ $usage->sold_qty }}</td>
                                                <td>{{ number_format($usage->unit_sale_price, 0, '.', '') ?? ' ' }}</td>
                                                <td>{{ $usage->sold_qty * $usage->unit_sale_price }}</td>
                                                <td>{{ \Carbon\Carbon::parse($usage->date)->format('d-M-Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="6" class="text-secondary">@lang('No Data Available')</td>
                                            </tr>
                                        @endforelse
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var category_id = $('#category_id').val();
            var itemSelect = $('#item_id');

            function populateItems(category_id) {
                if (category_id) {
                    $.ajax({
                        url: '{{ route('get-items', ':category_id') }}'.replace(':category_id',
                            category_id),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            itemSelect.empty();
                            itemSelect.append('<option value="">@lang('Select Item')</option>');
                            $.each(data, function(key, value) {
                                itemSelect.append('<option value="' + value.id + '"' +
                                    (value.id == "{{ request('item_id') }}" ? ' selected' :
                                        '') +
                                    '>' + value.title + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                        }
                    });
                } else {
                    itemSelect.empty();
                    itemSelect.append('<option value="">@lang('Select Item')</option>');
                }
            }

            $('#category_id').change(function() {
                populateItems($(this).val());
            });

            // Populate items on page load if category is already selected
            if (category_id) {
                populateItems(category_id);
            }
        });
    </script>

@endsection

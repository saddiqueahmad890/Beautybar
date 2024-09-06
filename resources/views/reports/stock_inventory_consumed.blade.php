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
                        <li class="breadcrumb-item active">@lang('Stock Inventory Consumed Report')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Stock Inventory Consumed Report')</h3>
                    <div class="card-tools">
                        <a class="btn btn-primary" target="_blank"
                            href="{{ route('reports.stock-inventory-consumed') }}?export=1&{{ http_build_query(request()->query()) }}">
                            <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                        </a>
                        <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i>
                            @lang('Filter')</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <form class="border mb-3" method="GET" action="{{ route('reports.stock-inventory-consumed') }}">
                            <input type="hidden" name="isFilterActive" value="true">

                            <div class="row col-12 p-0 m-0">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>@lang('Category')</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            </div>
                                            <select name="category_id" id="category_id" class="form-control" >
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
                                            <select id="item_id" name="item_id"
                                                class="form-control @error('item_id') is-invalid @enderror" >
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
                                        @error('item_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
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
                                        <a href="{{ route('reports.stock-inventory-consumed') }}"
                                            class="btn btn-secondary">@lang('Clear')</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="row col-12 p-0 m-0">
                        <div class="card col-12 m-0 p-0">

                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                @php
                                    $totalConsumedQuantity = 0;
                                    $totalUnitCost = 0;
                                    $totalCost = 0;
                                @endphp

                                @forelse ($inventoryUsages as $usage)
                                    @php
                                        $totalConsumedQuantity += $usage->consumed_quantity;
                                        $totalUnitCost += $usage->unit_cost ?? 0; // If unit cost is needed
                                        $totalCost += $usage->consumed_quantity * ($usage->unit_cost ?? 0); // If total cost is needed
                                    @endphp
                                @empty
                                @endforelse

                                <table class="table table-bordered custom-table bg-light">
                                    <thead>
                                        <tr>
                                            <th>@lang('Category')</th>
                                            <th>@lang('Item')</th>
                                            <th>@lang('Consumed Qty') <br> Totals {{ $totalConsumedQuantity }}</th>
                                            {{-- <th>@lang('Unit Cost')</th>
                                                <th>@lang('Total Cost')</th> --}}
                                            {{-- <th>@lang('Supplier')</th> --}}
                                            <th>@lang('Date')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($inventoryUsages as $usage)
                                            <tr>
                                                <td>{{ $usage->inventory->item->category->title ?? '-' }}</td>
                                                <td>{{ $usage->inventory->item->title ?? '-' }}</td>
                                                <td>{{ $usage->consumed_quantity ?? '-' }}</td>
                                                {{-- <td>{{ $usage->unit_cost ?? '0' }}</td>
                                                     <td>{{ $usage->consumed_quantity * ($usage->unit_cost ?? 0) }}</td> --}}
                                                {{-- <td>{{ $usage->supplier ?? '-' }}</td> --}}
                                                <td>{{ \Carbon\Carbon::parse($usage->date)->format('d-M-Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="4" class="text-secondary">@lang('No Data Available')</td>
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

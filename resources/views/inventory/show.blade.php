@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('inventories.create', ['type' => request()->query('type')]) }} "
                            class="btn btn-outline btn-info">
                            + @lang('Add Inventory')
                        </a>
                        <a href="{{ route('inventories.index', ['type' => request()->query('type')]) }}"
                            class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i>
                            @lang('View Inventory')
                        </a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                href="{{ route('inventories.index', ['type' => request()->query('type')]) }}">@lang('Inventories')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Inventory List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Inventory List')</h3>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-4 ">
                            <div class="form-group">
                                <label for="category_id">@lang('Category') <b class="text-danger"></b></label>
                                <p>{{ $inventory->category->title }}</p>
                                @error('category')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4 ">
                            <div class="form-group">
                                <label for="item_id">@lang('Item') <b class="text-danger"></b></label>
                                <p>{{ $inventory->item->title }}</p>
                                @error('item_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4 ">
                            <div class="form-group">
                                <label for="subcategory_id">@lang('Supplier Name')</label>
                                <p>{{ $inventory->supplier ?? '-' }}</p>
                                @error('item_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-4">
                            <div class="form-group">
                                <label for="Quantity">@lang('Quantity') <b class="text-danger"></b></label>
                                <p id="current_quantity">{{ $inventory->quantity }}</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="unit_cost">@lang('Unit Cost') <b class="text-danger"></b></label>
                                <p id="unit_cost_value">{{ $inventory->unit_cost }}</p>
                            </div>
                        </div>
                        @if (request()->query('type') !== 'ofs_items')
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="unit_sale">@lang('Unit sale') <b class="text-danger"></b></label>
                                    <p id="unit_sale_value">{{ $inventory->unit_sale ?? ' ' }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="col-4">
                            <div class="form-group">
                                <label for="description">@lang('Description') <b class="text-danger"></b></label>
                                <p id="description">{{ $inventory->description ?? ' ' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if (request()->query('type') == 'ofss_items')
                    {{-- <div class="row">
                            <div class="col-md-12">
                                <div class="card-header bg-primery">
                                    <h3 class="card-title">@lang('Record Usage ')(add 0 price in case of product consumed) </h3>
                                </div>
                                <form id="usage-form" class="bg-custom p-2 m-0" method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label for="consume_quantity">@lang('Consume/Sold Quantity')</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                </div>
                                                <input type="number" name="consume_quantity" id="consume_quantity"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="date">@lang('Date')</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input type="date" name="date" id="date" class="form-control"
                                                    value="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="unit_sale_price">@lang('Unit Sale Price (PKR)')</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"></span>
                                                </div>
                                                <input type="number" name="unit_sale_price" id="unit_sale_price"
                                                    min="0" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
                                    <button type="submit" class="btn btn-primary mt-3">@lang('Submit')</button>
                                </form>
                                <div id="response-message" class="mt-3"></div>
                            </div>
                        </div> --}}

                    {{-- <div class="row col-12 p-0 m-0 mt-4">
                            <div class="card col-12 p-0 m-0">
                            <div class="card-header bg-info">
                                <h4 class="card-title">@lang('Inventory history')</h4>
                                <a class="btn btn-primary float-right" target="_blank"
                                href="{{ route('inventories.show', ['inventory' => $inventory->id]) }}?export=1">
                                <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                            </a> --}}

                    {{-- </div> --}}

                    {{-- <div class="card-body" style="height: 500px; overflow-y: auto;">
                                <table class="table table-bordered custom-table" id="laravel_datatable">
                                    @php
                                        $usages = $inventory->usages->sortByDesc('id');
                                        $totalPurchasedQty = $usages->sum('purchased_qty');
                                        $totalConsumedQty = $usages->sum('consumed_quantity');
                                        $totalCost = $usages->sum('unit_cost');
                                        $totalSoldQty = $usages->sum('sold_qty');
                                        @endphp
                                    <thead>
                                        <tr>
                                            <th>@lang('Purchased Qty')<br><strong>Total:</strong>
                                                {{ $totalPurchasedQty }}</th>
                                                <th>@lang('Unit Cost')<br><strong>Total :</strong> {{ $totalCost }} PKR
                                                </th>
                                            <th>@lang('Consumed Qty')<br><strong>Total :</strong>
                                                {{ $totalConsumedQty }}</th>
                                            <th>@lang('Sold Qty')<br><strong>Total :</strong> {{ $totalSoldQty }}</th>
                                            <th>@lang('Unit Sale Price')</th>
                                            <th>@lang('Date')</th>
                                            <th>@lang('User')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($usages as $usage)
                                        <tr>
                                            <td>{{ $usage->purchased_qty ?? '' }}</td>
                                            <td>{{ $usage->unit_cost ? $usage->unit_cost . ' PKR' : '' }}</td>
                                            <td>{{ $usage->consumed_quantity ?? '' }}</td>
                                            <td>{{ $usage->sold_qty ?? '' }}</td>
                                            <td>{{ $usage->unit_sale_price ? $usage->unit_sale_price . ' PKR' : '' }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($usage->date)->format('d-M-Y') }}</td>
                                            <td>{{ optional($usage->createdBy)->name ?? '' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7">No usages found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                        </div> --}}
                @endif
            </div>
        </div>
    </div>
    </div>
    </div>

    @include('layouts.delete_modal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            function validateUnitSalePrice() {
                const unitSalePriceInput = document.getElementById('unit_sale_price');
                const unitSale = parseFloat(document.getElementById('unit_sale_value').innerText);
                const unitSalePrice = parseFloat(unitSalePriceInput.value);
                if (unitSalePrice !== 0 && unitSalePrice <= unitSale) {
                    unitSalePriceInput.setCustomValidity('Value must be 0 or greater than Unit Sale');
                } else {
                    unitSalePriceInput.setCustomValidity('');
                }
            }

            document.getElementById('unit_sale_price').addEventListener('input', validateUnitSalePrice);

            function handleFormSubmit(event) {
                event.preventDefault(); // Prevent the default form submission

                const formData = $('#usage-form').serialize();
                const currentQuantity = parseInt($('#current_quantity').text());
                const consumeQuantity = parseInt($('#consume_quantity').val());

                if (consumeQuantity > currentQuantity) {
                    $('#response-message').html(
                        '<div class="alert alert-danger">Consume quantity cannot be greater than current quantity.</div>'
                    );
                    return;
                }

                $.ajax({
                    url: '{{ route('inventories.update_quantity', $inventory->id) }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#response-message').html(
                            '<div class="alert alert-success">Quantity updated successfully.</div>'
                        );
                        $('#current_quantity').text(response
                            .new_quantity); // Update the displayed quantity
                        $('#usage-form')[0].reset(); // Reset the form fields

                        const data = response;
                        console.log(data);

                        const newRow = `<tr>
                        <td>${data.purchased_qty || '0'}</td>
                        <td>${data.unit_cost || '0'} PKR</td>
                        <td>${data.usedInventory.consumed_quantity || '0'}</td>
                        <td>${data.sold_qty || '0'}</td>
                        <td>${data.unit_sale_price || '0'} PKR</td>
                        <td>${data.usedInventory.date}</td>
                        <td>${response.username}</td>
                    </tr>`;

                        $('#laravel_datatable tbody').prepend(newRow);
                        location.reload();
                    },
                    error: function(xhr) {
                        $('#response-message').html(
                            '<div class="alert alert-danger">An error occurred while updating the quantity.</div>'
                        );
                    }
                });
            }

            // Attach event listener for form submission
            $('#usage-form').on('submit', handleFormSubmit);
        });
    </script>
@endsection

@extends('layouts.layout')
@push('header')
    @if (old('account_name') || isset($invoice->invoiceItems))
        <meta name="clear-invoice-html" content="clean">
    @endif
    <meta name="invoice-total" content="{{ old('total', $invoice->total ?? 0) }}">
    <meta name="invoice-grand-total" content="{{ old('grand_total', $invoice->grand_total ?? 0) }}">
    {{-- <meta name="base-url" content="{{ config('app.url') }}"> --}}

@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('invoice.index', ['type' => request()->query('type')]) }}"
                            class="btn btn-outline btn-info"><i class="fas fa-eye"></i>
                            @lang('View All')</a>

                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('invoice.index', ['type' => request()->query('type')]) }}">@lang('Invoice')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Update Invoice')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Update Invoice')</h3>
                </div>
                <div class="card-body">
                    <form class="form-material form-horizontal bg-custom"
                        action="{{ route('invoices.update', $invoice) . '?type=' . request()->query('type') }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row col-12 p-0 m-0">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group">
                                    <label for="user_id">@lang('Customer')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="user_id"
                                            class="form-control select2 @error('user_id') is-invalid @enderror"
                                            id="user_id" required>
                                            <option value="" selected>Select Customer</option>
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}"
                                                    @if (old('user_id', $invoice->user_id) == $patient->id) selected @endif>
                                                    {{ $patient->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('user_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Invoice Date') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="text" name="invoice_date" id="invoice_date"
                                            class="form-control @error('invoice_date') is-invalid @enderror"
                                            placeholder="@lang('Invoice Date')"
                                            value="{{ old('invoice_date', $invoice->invoice_date) }}" readonly required>
                                    </div>
                                    @error('invoice_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row  col-12 p-0 m-0">
                            <div class="col-md-12 ">
                                <div class="table-responsive ">
                                    <table id="t1" class="table">
                                        <thead>
                                            <tr>


                                                <th scope="col" class="custom-th-width-20">@lang('items')
                                                </th>
                                                <th scope="col" class="custom-th-width-20">@lang('Quantity')
                                                </th>

                                                <th scope="col" class="custom-th-width-20">@lang('Price')</th>

                                                <th scope="col" class="custom-th-width-20">@lang('Sub Total')</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (old('account_name'))
                                            @else
                                                @foreach ($invoice->invoiceItems as $invoiceItem)
                                                    <tr>

                                                        <td>
                                                            @php
                                                                $inventory = \App\Models\Inventory::find(
                                                                    $invoiceItem->inventory_id,
                                                                );
                                                            @endphp
                                                            <input type="text" class="form-control" id="inventory_id"
                                                                value="{{ $invoiceItem->inventory_id ??'' }}"
                                                                readonly>
                                                               
                                                                
                                                        </td>




                                                        <td>
                                                            <input type="number" step="1" name="quantity[]"
                                                                class="form-control quantity"
                                                                value="{{ $invoiceItem->quantity }}"
                                                                placeholder="@lang('Quantity')" readonly>
                                                        </td>


                                                        <td>
                                                            <input type="number" step=".01" name="price[]"
                                                                class="form-control price"
                                                                value="{{ $invoiceItem->price }}"
                                                                placeholder="@lang('Price')" readonly>
                                                        </td>



                                                        <td>
                                                            <input type="number" step=".01" name="sub_total[]"
                                                                class="form-control sub_total"
                                                                value="{{ $invoiceItem->total }}"
                                                                placeholder="@lang('Sub Total')" readonly>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>

                                        <tbody>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td class="ambitious-right">@lang('Total')</td>
                                                <td>
                                                    <input type="number" step=".01" name="total"
                                                        class="form-control total"
                                                        value="{{ old('total', $invoice->total) }}"
                                                        placeholder="@lang('Total')" readonly>
                                                </td>
                                                <td></td>
                                            </tr>
                                           
                                            <tr>
                                                <td colspan="2"></td>
                                                <td class="text-right">@lang('Vat')</td>
                                                <td class="text-right">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                        <input type="number" step=".01" name="vat_percentage"
                                                            value="{{ old('vat_percentage', $invoice->vat_percentage) }}"
                                                            class="form-control vat_percentage" placeholder="%">

                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" step=".01" name="total_vat"
                                                        class="form-control vat"
                                                        value="{{ old('total_vat', $invoice->total_vat) }}"
                                                        placeholder="@lang('Total Discount')">

                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td class="ambitious-right">@lang('Grand Total')</td>
                                                <td>
                                                    <input type="number" step=".01" name="grand_total"
                                                        class="form-control grand_total"
                                                        value="{{ old('grand_total', $invoice->grand_total) }}"
                                                        placeholder="@lang('Grand Total')" readonly>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td class="ambitious-right">@lang('Paid')</td>
                                                <td>
                                                    <input type="number" step=".01" name="paid"
                                                        class="form-control paid"
                                                        value="{{ old('paid', $invoice->paid) }}"
                                                        placeholder="@lang('Paid')">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td class="ambitious-right">@lang('Due')</td>
                                                <td>
                                                    <input type="number" step=".01" name="due" id='due'
                                                        class="form-control due" value="{{ old('due', $invoice->due) }}"
                                                        placeholder="@lang('Due')" readonly>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="d-flex gap-2">
                                        <input type="submit" id="submitButton" value="{{ __('Submit') }}"
                                            class="btn btn-outline btn-info btn-lg" />
                                        <a href="{{ route('invoice.index') }}?type={{ request()->query('type') }}"
                                            class="btn btn-outline btn-warning btn-lg">
                                            {{ __('Cancel') }}
                                        </a>

                                        <a href="{{ route('invoices.show', $invoice->id) }}?type={{ request()->query('type') }}"
                                            id="printButton" class="btn btn-primary btn-lg">{{ __('Print Invoice') }}</a>


                                    </div>
                                </div>
                            </div>
                        </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Use event delegation for dynamic elements
            $(document).on('change', '#procedure_categories', function() {
                var categoryId = $(this).val();
                var $procedureSelect = $(this).closest('tr').find('.procedure');
                var $priceInput = $(this).closest('tr').find('.price');

                $.ajax({
                    url: '{{ route('get.procedures.by.category') }}',
                    type: 'GET',
                    data: {
                        category_id: categoryId
                    },
                    success: function(data) {
                        $procedureSelect.empty().append(
                            '<option value=""  selected>Select Services</option>');
                        $.each(data, function(key, procedure) {
                            $procedureSelect.append('<option value="' + procedure.id +
                                '" data-price="' + procedure.price + '">' +
                                procedure.title + '</option>');
                        });
                    }
                });
            });

            // Use event delegation for dynamic elements
            $(document).on('change', '.procedure', function() {
                var price = $(this).find(':selected').data('price');
                var $row = $(this).closest('tr');
                var $priceInput = $row.find('.price');
                var $quantityInput = $row.find('.quantity');
                var $subTotalInput = $row.find('.sub_total');

                // Set price value
                $priceInput.val(price);

                // Automatically change quantity from 0 to 1
                if ($quantityInput.val() == 0) {
                    $quantityInput.val(1);
                }

                // Update sub_total
                var subTotal = price * parseFloat($quantityInput.val());
                $subTotalInput.val(subTotal.toFixed(2));

                // Update totals
                updateTotals();
            });

            // Function to update totals
            function updateTotals() {
                var total = 0;

                // Calculate the sum of sub_totals
                $('.sub_total').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });

                $('.total').val(total.toFixed(2));
                $('.grand_total').val(total.toFixed(2));
            }


            // Use event delegation for dynamic elements
            $(document).on('click', '.m-remove', function() {
                $(this).closest('tr').remove();
                updateTotals();
            });

            // Update totals when quantity or price changes
            $(document).on('change keyup', '.quantity, .price', function() {
                var $row = $(this).closest('tr');
                var $quantityInput = $row.find('.quantity');
                var $priceInput = $row.find('.price');
                var $subTotalInput = $row.find('.sub_total');

                var subTotal = parseFloat($quantityInput.val()) * parseFloat($priceInput.val());
                $subTotalInput.val(subTotal.toFixed(2));

                updateTotals();
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // JavaScript for dynamic behavior if needed
            document.querySelectorAll('.m-add').forEach(button => {
                button.addEventListener('click', () => {
                    // Add row logic here
                });
            });
            document.querySelectorAll('.m-remove').forEach(button => {
                button.addEventListener('click', () => {
                    // Remove row logic here
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const dueField = document.getElementById("due");
            const submitButton = document.getElementById("submitButton");
            const printButton = document.getElementById("printButton");

            function toggleSubmitButton() {
                if (parseFloat(dueField.value) === 0) {
                    submitButton.style.display = "none";
                    printButton.style.display = "block";
                } else {
                    submitButton.style.display = "block";
                    printButton.style.display = "none";
                }
            }

            // Initial check
            toggleSubmitButton();

            // If due value changes dynamically, you can add an event listener here
            dueField.addEventListener("input", toggleSubmitButton);
            dueField.addEventListener("change", toggleSubmitButton);

            // Print function
            window.printPage = function() {
                window.print();
            };
        });
    </script>

@endsection
@push('footer')
    <script src="{{ asset('assets/js/custom/invoice.js') }}"></script>
@endpush

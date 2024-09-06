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
                                <a href="{{ route('invoice.index',['type' => request()->query('type')]) }}">@lang('Services Invoice')</a>
                            </li>
                            <li class="breadcrumb-item active">@lang('Add Services Invoice')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">@lang('Add Services Invoice')</h3>
                    </div>
                    <div class="card-body">
                        <form class="form-material form-horizontal bg-custom"
                            action="{{ route('invoice.store', ['type' => request()->query('type')]) }}" method="POST">
                            @csrf
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
                                                <option value="" disabled selected>Select Customer</option>
                                                @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}"
                                                    {{ isset($selectedPatientId) && $selectedPatientId == $patient->id ? 'selected' : (old('patient') == $patient->id ? 'selected' : '') }}>
                                                    {{ $patient->name }} - {{ $patient->patientDetails->mrn_number ?? ' ' }}
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
                                                class="form-control flatpickr @error('invoice_date') is-invalid @enderror"
                                                placeholder="@lang('Invoice Date')"
                                                value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                        </div>
                                        @error('invoice_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="procedure_id" value="1">

                            <div class="row col-12 p-0 m-0">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="t1" class="table">
                                            <thead>
                                                <tr>
                                                        <th scope="col" class="custom-th-width-15">@lang('Employee')</th>
                                                        <th scope="col" class="custom-th-width-15">@lang('Services Category')</th>
                                                        <th scope="col" class="custom-th-width-15">@lang('Services')
                                                        </th>
                                                   
                                                    <th scope="col" class="custom-th-width-14">@lang('Price')</th>
                                                        <th scope="col" class="custom-th-width-14">@lang('Discount %')
                                                        </th>
                                                    <th scope="col" class="custom-th-width-14">@lang('Sub Total')</th>
                                                        <th scope="col" class="custom-white-space">@lang('Add / Remove')
                                                        </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (old('account_name'))
                                                    @foreach (old('account_name') as $key => $value)
                                                        <tr>
                                                                <td>
                                                                    <select name="doctors[]" class="form-control doctors"
                                                                        required>
                                                                        <option value="">--@lang('Select')--
                                                                        </option>
                                                                        @foreach ($doctors as $doctor)
                                                                            <option value="{{ $doctor->id }}">
                                                                                {{ $doctor->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="account_name[]"
                                                                        id="account_name"
                                                                        value="{{ $accountHeader->name }}">
                                                                    <select name="procedure_categories[]"
                                                                        class="form-control" required>
                                                                        <option value="">--@lang('Select')--
                                                                        </option>
                                                                        @foreach ($procedureCategories as $procedureCategory)
                                                                            <option value="{{ $procedureCategory->id }}"
                                                                                @if (old('procedure_categories')[$key] == $procedureCategory->id) selected @endif>
                                                                                {{ $procedureCategory->title }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="procedure[]"
                                                                        class="form-control"
                                                                        value="{{ old('procedure')[$key] }}"
                                                                        placeholder="@lang('Select Procedure ')">

                                                                </td>
                                                            

                                                                <input type="hidden" class="form-control quantity"
                                                                    name="quantity[]" value="1" min="0">
                                                         

                                                           
                                                                <td>
                                                                    <input type="number" step=".01" name="price[]"
                                                                        class="form-control price" value=""
                                                                        placeholder="@lang('Price')" readonly>
                                                                </td>
                                                           
                                                           

                                                                <td>
                                                                    <input type="number" step=".01"
                                                                        name="discount_pct[]"
                                                                        value="{{ old('discount_pct', '0.00') }}"
                                                                        class="form-control discount_pct" placeholder="%"
                                                                        min="0.00" max="100.00">
                                                                </td>
                                                            <td>
                                                                <input type="number" step=".01" name="sub_total[]"
                                                                    class="form-control sub_total"
                                                                    placeholder="@lang('Sub Total')" readonly>
                                                            </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-info m-add"><i
                                                                            class="fas fa-plus"></i></button>
                                                                    <button type="button"
                                                                        class="btn btn-info m-remove"><i
                                                                            class="fas fa-trash"></i></button>
                                                                </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <tbody id="invoice">
                                                <tr>
                                                        <td>

                                                            <select name="doctors[]" class="form-control doctors"
                                                                id="doctors" required>
                                                                <option value="" disabled selected>Select Employee
                                                                </option>
                                                                @foreach ($doctors as $doctor)
                                                                    <option value="{{ $doctor->id }}">
                                                                        {{ $doctor->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="account_name[]" id="account_name"
                                                                value="{{ $accountHeader->name }}">
                                                            <select name="procedure_categories[]"
                                                                class="form-control procedure-category"
                                                                id="procedure_categories">
                                                                <option value="" disabled selected>Select Services
                                                                    Category
                                                                </option>
                                                                @foreach ($procedureCategories as $procedureCategory)
                                                                    <option value="{{ $procedureCategory->id }}">
                                                                        {{ $procedureCategory->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <select name="procedure[]" class="form-control procedure"
                                                                id="procedure">
                                                                <option value="" disabled selected>Select Services
                                                                </option>
                                                            </select>
                                                        </td>

                                                    
                                                        <input type="hidden" class="form-control quantity"
                                                            name="quantity[]" value="1" min="0">
                                                  


                                                        <td>
                                                            <input type="number" step=".01" name="price[]"
                                                                class="form-control price" value=""
                                                                placeholder="@lang('Price')" readonly>
                                                        </td>
                                                  

                                                        <td>

                                                            <input type="number" step=".01" name="discount_pct[]"
                                                                value="{{ old('discount_pct', '0.00') }}"
                                                                class="form-control discount_pct" placeholder="%"
                                                                min="0.00" max="100.00">

                                                        </td>


                                                    <td>
                                                        <input type="number" step=".01" name="sub_total[]"
                                                            class="form-control sub_total"
                                                            placeholder="@lang('Sub Total')" readonly>
                                                    </td>
                                                        <td>
                                                            <button type="button" class="btn btn-info m-add"><i
                                                                    class="fas fa-plus"></i></button>
                                                            <button type="button" class="btn btn-info m-remove"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </td>
                                                </tr>
                                            </tbody>
                                            <tbody>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td class="ambitious-right">@lang('Total')</td>
                                                    <td>
                                                        <input type="number" step=".01" name="total"
                                                            class="form-control total" value="{{ old('total', '0.00') }}"
                                                            placeholder="@lang('Total')" readonly>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <td class="text-right">
                                                            @lang('Total Discount $')
                                                        </td>
                                                        <td class="text-right">
                                                            <input type="text" step=".01" name="total_discount"
                                                                class="form-control total_discount"
                                                                value="{{ old('total_discount', '0.00') }}"
                                                                placeholder="@lang('Total Discount')" readonly>
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
                                                                value="{{ old('vat_percentage', '0.00') }}"
                                                                class="form-control vat_percentage" placeholder="%">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="number" step=".01" name="total_vat"
                                                            class="form-control vat"
                                                            value="{{ old('total_vat', '0.00') }}"
                                                            placeholder="@lang('Total Vat')" readonly>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td class="ambitious-right">@lang('Grand Total')</td>
                                                    <td>
                                                        <input type="number" step=".01" name="grand_total"
                                                            class="form-control grand_total"
                                                            value="{{ old('grand_total', '0.00') }}"
                                                            placeholder="@lang('Grand Total')" readonly>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td class="ambitious-right">@lang('Paid')</td>
                                                    <td>
                                                        <input type="number" step="1" name="paid"
                                                            class="form-control paid" value="{{ old('paid', '0') }}"
                                                            placeholder="@lang('Paid')">
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td class="ambitious-right">@lang('Due')</td>
                                                    <td>
                                                        <input type="number" step=".01" name="due"
                                                            class="form-control due" value="{{ old('due') }}"
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 col-form-label"></label>
                                        <div class="col-md-8">
                                            <input type="submit" value="{{ __('Submit') }}"
                                                class="btn btn-outline btn-info btn-lg" />
                                            <a href="{{ route('invoice.index', ['type' => request()->query('type')]) }}"
                                                class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
                                        </div>
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

                // Initial calculation on page load for hardcoded values
                function initialCalculation() {
                    $('.quantity').each(function() {
                        var $row = $(this).closest('tr');
                        var $quantityInput = $row.find('.quantity');
                        var $priceInput = $row.find('.price');
                        var $subTotalInput = $row.find('.sub_total');

                        var subTotal = parseFloat($quantityInput.val()) * parseFloat($priceInput.val());
                        $subTotalInput.val(subTotal.toFixed(2));
                    });

                    updateTotals();
                }

                // Event handler for procedure category change
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
                                '<option value="" disabled selected>Select Services</option>');
                            $.each(data, function(key, procedure) {
                                $procedureSelect.append('<option value="' + procedure.id +
                                    '" data-price="' + procedure.price + '">' +
                                    procedure.title + '</option>');
                            });
                        }
                    });
                });

                // Event handler for procedure change
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

                    updateTotals();
                });

                // Event handler for removing a row
                $(document).on('click', '.m-remove', function() {
                    $(this).closest('tr').remove();
                    updateTotals();
                });

                // Event handler for quantity or price change
                $(document).on('change keyup', '.quantity, .price', function() {
                    var $row = $(this).closest('tr');
                    var $quantityInput = $row.find('.quantity');
                    var $priceInput = $row.find('.price');
                    var $subTotalInput = $row.find('.sub_total');

                    var subTotal = parseFloat($quantityInput.val()) * parseFloat($priceInput.val());
                    $subTotalInput.val(subTotal.toFixed(2));

                    updateTotals();
                });

                // Call the initial calculation function on page load
                initialCalculation();
            });
        </script>
    @endsection

    @push('footer')
        <script src="{{ asset('assets/js/custom/invoice.js') }}"></script>
    @endpush

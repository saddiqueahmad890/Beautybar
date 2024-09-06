@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('inventories.index', ['type' => request()->query('type')]) }}"
                            class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View Inventory')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('inventories.index') }}?type={{ request()->query('type') }}">@lang('Inventories')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Create Inventory')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title d-inline">@lang('Create Inventories')</h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form id="subcategoryForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('inventories.store') }}?type={{ request()->query('type') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="Supplier">@lang('Supplier Name') <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control" id="supplier" name="supplier"
                                        placeholder="xyz">
                                    @error('supplier')
                                        <div class="invalid-feedback" required>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">@lang('Category') <b class="text-danger">*</b></label>
                                    <select id="category_id" name="category_id"
                                        class="form-control @error('item_id') is-invalid @enderror" required>
                                        <option value="">@lang('Select Category')</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"> {{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="item_id">@lang('Item') <b class="text-danger">*</b></label>
                                    <select id="item_id" name="item_id"
                                        class="form-control @error('item_id') is-invalid @enderror" required>
                                        <option value="">@lang('Select Item')</option>
                                    </select>
                                    @error('item_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="Quantity">@lang('Quantity') <b class="text-danger">*</b></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="0"
                                        placeholder="0">
                                    @error('quantity')
                                        <div class="invalid-feedback" required>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="unit cost">@lang('Unit Cost') <b class="text-danger">*</b></label>
                                    <input type="number" class="form-control" id="unit_cost" name="unit_cost"
                                        min="0" placeholder="0">
                                    @error('unit_cost')
                                        <div class="invalid-feedback" required>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            @if (request()->query('type') !== 'ofs_items')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="unit sale">@lang('Unit Sale') <b class="text-danger">*</b></label>
                                        <input type="number" class="form-control" id="unit_sale" name="unit_sale"
                                            min="0" placeholder="0">
                                        @error('unit_sale')
                                            <div class="invalid-feedback" required>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="description">@lang('Description') <b class="text-danger"></b></label>
                                    <input type="text" class="form-control" id="description" name="description"
                                        placeholder="Description">
                                    @error('description')
                                        <div class="invalid-feedback" required>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-8 offset-md-0">
                                        <button type="submit"
                                            class="btn btn-outline btn-info btn-lg">{{ __('Create') }}</button>
                                        <a href="{{ route('inventories.index') }}?type={{ request()->query('type') }}"
                                            class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if (request()->query('type') == 'ofss_items')
                    <div class="card-body">
                        <div class="card-header bg-info">
                            <h3 class="card-title">@lang('Inventory History')</h3>
                        </div>
                        <div class="card-body bg-custom">
                            <table class="table table-bordered bg-light">
                                <thead>
                                    <tr>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Supplier Name')</th>
                                        <th>@lang('Category')</th>
                                        <th>@lang('Item')</th>
                                        <th>@lang('Purchased Quantity')</th>
                                        <th>@lang('Unit Price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventoryUsages as $usage)
                                        <tr>
                                            <td>{{ $usage->date }}</td>
                                            <td>{{ $usage->supplier ?? ' ' }}</td>
                                            <td>{{ $usage->inventory->category->title ?? ' ' }}</td>
                                            <td>{{ $usage->inventory->item->title ?? ' ' }}</td>
                                            <td>{{ $usage->purchased_qty ?? ' ' }}</td>
                                            <td>{{ $usage->unit_sale_price ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#category_id').change(function() {
                var category_id = $(this).val();
                if (category_id) {
                    $.ajax({
                        url: '{{ route('get-items', ':category_id') }}'.replace(':category_id',
                            category_id),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#item_id').empty();
                            $('#item_id').append(
                                '<option value="">@lang('Select Item')</option>');
                            $.each(data, function(key, value) {
                                $('#item_id').append('<option value="' + value.id +
                                    '">' + value.title + '</option>');
                            });
                        }
                    });
                } else {
                    $('#item_id').empty();
                    $('#item_id').append('<option value="">@lang('Select Item')</option>');
                }
            });
        });
    </script>
@endsection

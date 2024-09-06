@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('inventories.create') }}?type={{ request()->query('type') }}"
                            class="btn btn-outline btn-info">
                            + @lang('Add Inventory')
                        </a>
                    </h3>
                    <h3>
                        <a href="{{ route('inventories.index') }}?type={{ request()->query('type') }}"
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
                            <h3 class="card-title d-inline">@lang('Update Inventories')</h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('inventories.update', $inventory->id) }}?type={{ request()->query('type') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- First Row -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="category_id">@lang('Category') <b class="text-danger">*</b></label>
                                    <select id="category_id" name="category_id"
                                        class="form-control @error('category_id') is-invalid @enderror" required readonly>
                                        <option value="{{ $inventory->category_id }}">{{ $inventory->category->title }}
                                        </option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="item_id">@lang('Item') <b class="text-danger">*</b></label>
                                    <select id="item_id" name="item_id"
                                        class="form-control @error('item_id') is-invalid @enderror" required readonly>
                                        <option value="{{ $inventory->item_id }}">{{ $inventory->item->title ?? '' }}
                                        </option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('item_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="supplier">@lang('Supplier Name') <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control" id="supplier" name="supplier"
                                        value="{{ $inventory->supplier }}" placeholder="xyz" required>
                                    @error('supplier')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quantity">@lang('Quantity') <b class="text-danger">*</b></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                        id="quantity" name="quantity" value="{{ $inventory->quantity }}" placeholder="0"
                                        required>
                                    @error('quantity')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Second Row -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="unit_cost">@lang('Unit Cost') <b class="text-danger">*</b></label>
                                    <input type="number" class="form-control @error('unit_cost') is-invalid @enderror"
                                        id="unit_cost" name="unit_cost" value="{{ $inventory->unit_cost }}" placeholder="0"
                                        required>
                                    @error('unit_cost')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            @if (request()->query('type') == 'sln_items')
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="unit_sale">@lang('Unit Sale') <b class="text-danger">*</b></label>
                                        <input type="number" class="form-control @error('unit_sale') is-invalid @enderror"
                                            id="unit_sale" name="unit_sale" value="{{ $inventory->unit_sale }}"
                                            placeholder="0" required>
                                        @error('unit_sale')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <input type="text" class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" value="{{ $inventory->description }}"
                                        placeholder="Description">
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-outline btn-info btn-lg">
                                        {{ __('Update') }}
                                    </button>
                                    <a href="{{ route('inventories.index') }}"
                                        class="btn btn-outline btn-warning btn-lg">
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

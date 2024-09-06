@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('items.create') }}?type={{ request()->query('type')}}" class="btn btn-outline btn-info">
                            + @lang('Add Item')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3>
                        <a href="{{ route('items.index') }}?type={{ request()->query('type')}}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('items.index') }}?type={{ request()->query('type')}}">@lang('Item')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Item')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Edit Item ({{ $item->category->title }})</h3>
                </div>
                <div class="card-body">
                    <form id="itemForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">@lang('Category') <b class="text-danger">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        </div>
                                        <select name="category_id"
                                            class="form-control select2 @error('category_id') is-invalid @enderror"
                                            id="category_id">
                                            <option value="" disabled>Select Category</option>
                                            @foreach ($categories as $categories)
                                                <option value="{{ $categories->id }}"
                                                    @if (old('category_id', $item->category_id) == $categories->id) selected @endif>
                                                    {{ $categories->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                          
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <b class="text-danger">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="title" name="title"
                                            value="{{ old('title', $item->title) }}"
                                            class="form-control @error('title') is-invalid @enderror"
                                            placeholder="@lang('Title')" required>
                                    </div>
                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                         
                             @if (request()->query('type') == 'ofs_items')   
                                <input type="hidden" id="type" name="type" value="ofs_items"> 
                            @else
                                <input type="hidden" id="type" name="type" value="sln_items">
                            @endif

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="item_date">@lang('Date')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                                        </div>
                                        <input type="text" name="item_date" id="item_date"
                                            class="form-control flatpickr @error('item_date') is-invalid @enderror"
                                            placeholder="@lang('Item_date')"
                                            value="{{ old('item_date', $item->item_date ?? '') }}">
                                        @error('item_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        </div>
                                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                            rows="5" placeholder="@lang('Description')">{{ old('description', $item->description) }}</textarea>
                                    </div>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="submit" value="@lang('Update')" class="btn btn-outline btn-info btn-lg">
                                <a href="{{ route('items.index') }}?type={{ request()->query('type')}}"
                                    class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

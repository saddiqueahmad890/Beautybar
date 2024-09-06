@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
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
                        <li class="breadcrumb-item active">@lang('Add Item')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Item')</h3>
                </div>
                <div class="card-body">
                    <form id="itemForm" class="form-material form-horizontal bg-custom" action="{{ route('items.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">@lang('Main Category') <b class="text-danger">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        </div>
                                        <select id="category_id" name="category_id"
                                            class="form-control select2 @error('category_id') is-invalid @enderror">
                                            <option value="">@lang('Select category')</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->title }}
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

                           
                            {{-- <div class="row">  --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <b class="text-danger">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="title" name="title" value="{{ old('title') }}"
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="item_date">@lang('Date')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                                        </div>
                                        <input type="text" name="item_date" id="item_date"
                                            class="form-control flatpickr @error('item_date') is-invalid @enderror"
                                            placeholder="@lang('Item_date')" value="{{ old('item_date', date('Y-m-d')) }}">
                                        @error('item_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                          @if (request()->query('type') == 'ofs_items')   
                                <input type="hidden" id="type" name="type" value="ofs_items"> 
                            @else
                                <input type="hidden" id="type" name="type" value="sln_items">
                            @endif
                       
                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        </div>
                                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                            rows="5" placeholder="@lang('Description')">{{ old('description') }}</textarea>
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
                                <input type="submit" value="@lang('Submit')" class="btn btn-outline btn-info btn-lg">
                                <a href="{{ route('items.index') }}?type={{ request()->query('type')}}"
                                    class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#category_id').change(function() {
                var categoryId = $(this).val();

                if (categoryId) {
                    $.ajax({
                        url: '{{ route('getsubcategories') }}',
                        type: 'GET',
                        data: {
                            category_id: categoryId
                        },
                        success: function(response) {
                            $('#subcategory_id').empty();
                            $('#subcategory_id').append(
                                '<option value="">@lang('Select subcategory')</option>');
                            $.each(response, function(key, value) {
                                $('#subcategory_id').append('<option value="' + value
                                    .id + '">' + value.title + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                } else {
                    $('#subcategory_id').empty();
                    $('#subcategory_id').append('<option value="">@lang('Select subcategory')</option>');
                }
            });
        });
    </script>
@endsection

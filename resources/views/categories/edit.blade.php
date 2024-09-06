@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="col-sm-6 d-flex">
                        @if (request()->query('type') == 'ofs_items')
                            <h3 class="mr-2">
                            <a href="{{ route('categories.create') }}?type={{ request()->query('type') }}" class="btn btn-outline btn-info">
                                + @lang('Add Office Category')
                            </a>
                            <span class="pull-right"></span>
                        </h3>
                        @else
                            <h3 class="mr-2">
                            <a href="{{ route('categories.create') }}?type={{ request()->query('type') }}" class="btn btn-outline btn-info">
                                + @lang('Add Salon Category')
                            </a>
                            <span class="pull-right"></span>
                        </h3>
                        @endif
                         @if (request()->query('type') == 'ofs_items')
                            <h3>
                            <a href="{{ route('categories.index') }}?type={{ request()->query('type') }}" class="btn btn-outline btn-info"> @lang('View Office Categories')</a>

                        </h3>
                        @else
                            <h3>
                            <a href="{{ route('categories.index') }}?type={{ request()->query('type') }}" class="btn btn-outline btn-info"> @lang('View Salon Categories')</a>

                        </h3>
                        @endif
                       
                    </div>
                    @can('categorie-create')
                        <h3>
                            <a href="{{ route('categories.index') }}?type={{ request()->query('type')}}" class="btn btn-outline btn-info">
                                <i class="fas fa-eye"></i> @lang('View Categories')
                            </a>
                            <span class="pull-right"></span>
                        </h3>
                    @endcan
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('categories.index') }}?type={{ request()->query('type')}}">@lang('Categorie')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Categories')</li>
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
                         @if (request()->query('type') == 'ofs_items')
                            <div class="col">
                            <h3 class="card-title d-inline">Edit Office Categories ({{ $category->title }})</h3>
                        </div>
                        @else
                             <div class="col">
                            <h3 class="card-title d-inline">Edit Salon Categories ({{ $category->title }})</h3>
                        </div>
                        @endif
                       
                    </div>
                </div>

                <div class="card-body">
                    <form id="categorieForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('categories.update', $category->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <b class="text-danger">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="title" name="title"
                                            value="{{ old('title', $category->title) }}"
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        </div>
                                        <input id="description" name="description"
                                            class="form-control @error('description') is-invalid @enderror" rows="5"
                                            placeholder="@lang('Description')"
                                            value="{{ old('description', $category->description) }}"></input>
                                    </div>
                                    @error('description')
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
                            

                           

                        </div>

                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-md-8 offset-md-0">
                                        <button type="submit" class="btn btn-outline btn-info btn-lg">
                                            {{ __('Update') }}
                                        </button>
                                        <a href="{{ route('categories.index') }}?type={{ request()->query('type')}}"
                                            class="btn btn-outline btn-warning btn-lg">
                                            {{ __('Cancel') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

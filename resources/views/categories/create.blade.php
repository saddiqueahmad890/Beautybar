@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>
                        @if (request()->query('type') == 'ofs_items')
                            <a href="{{ route('categories.index', ['type' => $type]) }}" class="btn btn-outline btn-info">
                                <i class="fas fa-eye"></i> @lang('View Office Categories')
                            </a>
                        @else
                            <a href="{{ route('categories.index', ['type' => $type]) }}" class="btn btn-outline btn-info">
                                <i class="fas fa-eye"></i> @lang('View Salon Categories')
                            </a>
                        @endif

                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('categories.index') }}?type={{ request()->query('type') }}">@lang('Category')</a>
                        </li>
                        @if (request()->query('type') == 'ofs_items')
                            <li class="breadcrumb-item active">@lang('Add Offices Category')</li>
                        @else
                            <li class="breadcrumb-item active">@lang('Add SAlon Category')</li>
                        @endif

                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    @if (request()->query('type') == 'ofs_items')
                        <h3 class="card-title">@lang('Add Offices Category')</h3>
                    @else
                        <h3 class="card-title">@lang('Add Salon Category')</h3>
                    @endif

                </div>
                <div class="card-body">
                    <form id="categoryForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                                            class="form-control @error('title') is-invalid @enderror"
                                            placeholder="@lang('Title')" required>
                                        @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
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
                                            placeholder="@lang('Description')" value= "{{ old('description') }}"></input>
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
                        <div class="form-group">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-8">
                                <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline btn-info btn-lg" />
                                <a href="{{ route('categories.index') }}?type={{ request()->query('type') }}"
                                    class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

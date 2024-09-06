@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h3>
                        <a href="{{ route('dd-investigations.index') }}" class="btn btn-outline btn-info">
                            @lang('View Investigations')
                        </a>
                    </h3> --}}
                    <h3>
                        <a href="{{ route('dd-investigations.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')</a>

                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dd-investigations.index') }}">@lang('Investigation')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Investigation')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Investigation')</h3>
                </div>
                <div class="card-body">
                    <form id="investigationsForm" class="form-material form-horizontal" action="{{ route('dd-investigations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="@lang('Title')" required>
                                        @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="description">@lang('Description')</label>
                                    <input id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="@lang('Description')" value="{{ old('description') }}">
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>@lang('Active')</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>@lang('Inactive')</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-8">
                                <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline btn-info btn-lg" />
                                <a href="{{ route('dd-investigations.index') }}" class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

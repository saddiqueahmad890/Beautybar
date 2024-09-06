@extends('layouts.layout')
@section('one_page_css')
    <link href="{{ asset('assets/css/quill.snow.css') }}" rel="stylesheet">
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2"><a href="{{ route('marital-statuses.create') }}" class="btn btn-outline btn-info">+
                            @lang('Add New Marital Status')</a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3><a href="{{ route('marital-statuses.index') }}" class="btn btn-outline btn-info"><i
                                class="fas fa-eye"></i> @lang('View All')</a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('marital-statuses.index') }}">@lang('All options')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Option')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Edit Marital Status ({{ $maritalStatus->name }})</h3>
                </div>
                <div class="card-body">
                    <form id="blooggroupForm" class="form-material form-horizontal"
                        action="{{ route('marital-statuses.update', $maritalStatus) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $maritalStatus->name) }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="@lang(' Name')" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8">
                                        <input type="submit" value="@lang('Update')"
                                            class="btn btn-outline btn-info btn-lg" />
                                        <a href="{{ route('marital-statuses.index') }}"
                                            class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
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
@push('footer')
    <script src="{{ asset('assets/js/quill.js') }}"></script>
@endpush

@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- @can('lab-create') --}}
                    <h3>
                        <a href="{{ route('labs.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i>@lang('View Labs')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    {{-- @endcan --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('labs.index') }}">@lang('Lab')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add A New Lab')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add A New Lab')</h3>
                </div>
                <div class="card-body">
                    <form id="labForm" class="form-material form-horizontal" action="{{ route('labs.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">@lang('Labortorist') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="@lang('Labotrist')" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">@lang('Email') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                        </div>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="@lang('Email')" required>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">@lang('Password') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="@lang('Password')" required>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="Title">@lang('Lab Name') <b class="text-danger">*</b></label>
                                    <input type="text" id="Title" name="Title" value="{{ old('Title') }}"
                                        class="form-control @error('Title') is-invalid @enderror"
                                        placeholder="@lang('Lab Name')" required>
                                    @error('Title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="PhoneNumber">@lang('Lab Phone')</label>
                                    <input type="text" id="PhoneNumber" name="PhoneNumber"
                                        value="{{ old('PhoneNumber') }}"
                                        class="form-control @error('PhoneNumber') is-invalid @enderror"
                                        placeholder="@lang('Lab Phone')">
                                    @error('PhoneNumber')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="LabDescription">@lang('Lab Description') <b class="text-danger">*</b></label>
                                    <textarea id="Description" name="Description" class="form-control @error('Description') is-invalid @enderror"
                                        rows="5" placeholder="@lang('Lab Description')" required>{{ old('Description') }}</textarea>
                                    @error('Description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="Address">@lang('Lab Address')</label>
                                    <textarea id="Address" name="Address" class="form-control @error('Address') is-invalid @enderror" rows="5"
                                        placeholder="@lang('Address')">{{ old('Lab Address') }}</textarea>
                                    @error('Address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-8">
                                <input type="submit" value="{{ __('Submit') }}"
                                    class="btn btn-outline btn-info btn-lg" />
                                <a href="{{ route('labs.index') }}"
                                    class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove if lab.js is not needed -->
    <script src="{{ asset('assets/js/custom/lab.js') }}"></script>
@endsection

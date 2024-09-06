@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('dd-medicine.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dd-medicine.index') }}">@lang('All Medicines')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add New Medicine')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add New Medicine')</h3>
                </div>
                <div class="card-body">
                    <form id="medicineForm" class="form-material form-horizontal" action="{{ route('dd-medicine.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="@lang('Name')" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dd_medicine_type">@lang('Medicine Type')</label>
                                    <div class="input-group mb-3">
                                        <select id="dd_medicine_type" name="dd_medicine_type"
                                            class="form-control @error('dd_medicine_type') is-invalid @enderror">
                                            <option value="">@lang('Select Medicine Type')</option>
                                            @foreach ($ddMedicineTypes as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ old('dd_medicine_type') == $type->id ? 'selected' : '' }}>
                                                    {{ ucfirst($type->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('dd_medicine_type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">@lang('Status') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                        </div>
                                        <select
                                            class="form-control ambitious-form-loading select2 @error('status') is-invalid @enderror"
                                            required name="status" id="status">
                                            <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>
                                                @lang('Active')</option>
                                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                                @lang('Inactive')</option>
                                        </select>
                                        @error('status')
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
                                        <input type="submit" value="{{ __('Submit') }}"
                                            class="btn btn-outline btn-info btn-lg" />
                                        <a href="{{ route('dd-medicine.index') }}"
                                            class="btn btn-outline btn-warning btn-lg">{{ __('Cancel') }}</a>
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

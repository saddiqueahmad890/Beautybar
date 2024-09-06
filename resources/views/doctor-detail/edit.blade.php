@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('doctor-details.create') }}" class="btn btn-outline btn-info">
                            + @lang('Add Employee')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    <h3>
                        <a href="{{ route('doctor-details.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('doctor-details.index') }}">@lang('Employee ')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Employee ')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Edit Employee ({{ $doctorDetail->user->name }}) </h3>
                </div>
                <div class="card-body">
                    <form id="departmentForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('doctor-details.update', $doctorDetail) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $doctorDetail->user->name) }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="@lang('Name')" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone">@lang('Phone')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" id="phone" name="phone"
                                            value="{{ old('phone', $doctorDetail->user->phone) }}"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="@lang('Phone')">
                                        @error('phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="email">@lang('Email') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                        </div>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', $doctorDetail->user->email) }}"
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="password">@lang('Password')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="@lang('Password')">
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_of_birth">@lang('Date of Birth')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                                        </div>
                                        <input type="text" name="date_of_birth" id="date_of_birth"
                                            class="form-control flatpickr @error('date_of_birth') is-invalid @enderror"
                                            placeholder="@lang('Date of Birth')"
                                            value="{{ old('date_of_birth', $doctorDetail->user->date_of_birth ?? '') }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="specialist">@lang('Specialist')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                                        </div>
                                        <input type="text" id="specialist" name="specialist"
                                            value="{{ old('specialist', $doctorDetail->specialist) }}"
                                            class="form-control @error('specialist') is-invalid @enderror"
                                            placeholder="@lang('Specialist')">
                                        @error('specialist')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="designation">@lang('Designation')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                        </div>
                                        <input type="text" id="designation" name="designation"
                                            value="{{ old('designation', $doctorDetail->designation) }}"
                                            class="form-control @error('designation') is-invalid @enderror"
                                            placeholder="@lang('Designation')">
                                        @error('designation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender">@lang('Gender')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        </div>
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror"
                                            id="gender">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="male" @if (old('gender', $doctorDetail->user->gender) == 'male') selected @endif>
                                                @lang('Male')</option>
                                            <option value="female" @if (old('gender', $doctorDetail->user->gender) == 'female') selected @endif>
                                                @lang('Female')</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="commission">@lang('Commission')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                        </div>
                                        <input type="text" id="commission" name="commission"
                                            value="{{ old('commission', $doctorDetail->commission) }}"
                                            class="form-control @error('commission') is-invalid @enderror"
                                            placeholder="@lang('Commission')">
                                        @error('commission')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">@lang('Status') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                        </div>
                                        <select
                                            class="form-control ambitious-form-loading @error('status') is-invalid @enderror"
                                            required name="status" id="status">
                                            <option value="1"
                                                {{ old('status', $doctorDetail->user->status) === '1' ? 'selected' : '' }}>
                                                @lang('Active')</option>
                                            <option value="0"
                                                {{ old('status', $doctorDetail->user->status) === '0' ? 'selected' : '' }}>
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




                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">@lang('Address')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                        </div>
                                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="1"
                                            placeholder="@lang('Address')">{{ old('address', $doctorDetail->user->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="col-md-6 m-0">
                                    <div class="form-group">
                                        <label for="biography">@lang('Biography')</label>
                                        <div class="col-md-12 mt-1 m-0 p-0">
                                            <input type="text" id="biography" name="biography" style="height: 55px;"                          value="{{ old('biography', $doctorDetail->biography) }}"
                                                class="form-control @error('biography') is-invalid @enderror"
                                                placeholder="@lang('Biography')">
                                            @error('biography')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo" class="col-md-12 col-form-label">
                                            <h4>{{ __('Photo') }}</h4>
                                        </label>
                                        <div class="col-md-12 m- p-0">
                                            <input id="photo" class="dropify" name="photo" type="file"
                                                data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2024K"
                                                @if ($doctorDetail->user->photo_url) data-default-file="{{ $doctorDetail->user->photo_url }}" @endif />
                                            <p>{{ __('Max Size: 2MB, Allowed Format: png, jpg, jpeg') }}</p>
                                        </div>
                                        @error('photo')
                                            <div class="error ambitious-red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8">
                                        <input type="submit" value="{{ __('Update') }}"
                                            class="btn btn-outline btn-info btn-lg" />
                                        <a href="{{ route('doctor-details.index') }}"
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

    <script src="{{ asset('assets/js/custom/doctor-detail.js') }}"></script>
@endsection

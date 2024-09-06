@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">@lang('User List')</a></li>
                        <li class="breadcrumb-item active">@lang('Create User')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3>@lang('Create User')</h3>
                </div>
                <div class="card-body">
                    <form id="userQuickForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label">
                                        <h4>@lang('Name') <b class="ambitious-crimson">*</b></h4>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input
                                            class="form-control ambitious-form-loading @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name') }}" id="name" type="text"
                                            placeholder="@lang('Type Your Name Here')" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label">
                                        <h4>@lang('Email') <b class="ambitious-crimson">*</b></h4>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                        </div>
                                        <input
                                            class="form-control ambitious-form-loading @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" id="email" type="email"
                                            placeholder="@lang('Type Your Email Here')" required>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label">
                                        <h4>@lang('Password') <b class="ambitious-crimson">*</b></h4>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input
                                            class="form-control ambitious-form-loading @error('password') is-invalid @enderror"
                                            name="password" id="password" type="password" placeholder="@lang('Type Your Password Here')"
                                            required>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label">
                                        <h4>@lang('Confirm Password') <b class="ambitious-crimson">*</b></h4>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                        </div>
                                        <input
                                            class="form-control ambitious-form-loading @error('password_confirmation') is-invalid @enderror"
                                            name="password_confirmation" id="password_confirmation" type="password"
                                            placeholder="@lang('Type Your Confirm Password Here')" required>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label">
                                        <h4>@lang('User For')</h4>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-users-cog"></i></span>
                                        </div>
                                        <select
                                            class="form-control ambitious-form-loading @error('role_for') is-invalid @enderror"
                                            name="role_for" id="role_for">
                                            <option value="0" {{ old('role_for') == 0 ? 'selected' : '' }}>
                                                @lang('System User')</option>
                                            <option value="1" {{ old('role_for') == 1 ? 'selected' : '' }}>
                                                @lang('General User')</option>
                                        </select>
                                        @error('role_for')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label">
                                        <h4>@lang('Phone')</h4>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input
                                            class="form-control ambitious-form-loading @error('phone') is-invalid @enderror"
                                            name="phone" value="{{ old('phone') }}" id="phone" type="text"
                                            placeholder="@lang('Type Phone Number Here')">
                                        @error('phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="staff_block">
                            <div class="row col-12 p-0 m-0">
                                
                          <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12 col-form-label">
                                            <h4>@lang('User Role')</h4>
                                        </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                            </div>
                                            <select
                                                class="form-control ambitious-form-loading @error('user_roles') is-invalid @enderror"
                                                name="user_roles" id="user_roles">
                                                @foreach ($userRoles as $key => $role)
                                                    <option value="{{ $key }}"
                                                        {{ old('user_roles') == $key ? 'selected' : '' }}>
                                                        {{ $role }}</option>
                                                @endforeach
                                            </select>
                                            @error('user_roles')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="user_block">
                            <div class="row col-12 p-0 m-0">

                              <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12 col-form-label">
                                            <h4>@lang('Staff Role')</h4>
                                        </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                            </div>
                                            <select
                                                class="form-control ambitious-form-loading @error('staff_roles') is-invalid @enderror"
                                                name="staff_roles" id="staff_roles">
                                                @foreach ($staffRoles as $key => $role)
                                                    <?php $role_show = $role;
                                                    if ($role == 'Doctor') {
                                                        $role_show = 'Employee';
                                                    }
                                                    if ($role == 'Patient') {
                                                        $role_show = 'Customer';
                                                    }
                                                    ?>
                                                    <option value="{{ $key }}"
                                                        {{ old('staff_roles') == $key ? 'selected' : '' }}>
                                                        {{ $role_show }}</option>
                                                @endforeach
                                            </select>
                                            @error('staff_roles')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="commission">@lang('Commission')</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="text" id="commission" name="commission"
                                                value="{{ old('commission') }}"
                                                class="form-control @error('commission') is-invalid @enderror"
                                                placeholder="@lang('Commission')">
                                            @error('commission')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <div class="invalid-feedback" id="js-invalid-feedback"
                                                style="display: none;">
                                                Commission must be between 1 and 100.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="user_company" name="user_company[]" value="1">
                            </div>
                        </div>
                        <div class="row col-12 m-0 p-0">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12 col-form-label">
                                        <h4>{{ __('Status') }}</h4>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                        </div>
                                        <select
                                            class="form-control ambitious-form-loading @error('status') is-invalid @enderror"
                                            required="required" name="status" id="status">
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="0">{{ __('Inactive') }}</option>
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
                                <label class="col-md-12 col-form-label">
                                    <h4>{{ __('Address') }}</h4>
                                </label>
                                <div class="col-md-12">
                                    <div id="input_address"
                                        class="@error('address') is-invalid @enderror description-min-height">
                                    </div>
                                    <input type="hidden" name="address" value="{{ old('address') }}" id="address">
                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="col-md-12 col-form-label">
                                    <h4>{{ __('Photo') }}</h4>
                                </label>
                                <div class="col-md-12">

                                    <input id="photo" class="dropify" name="photo" value="{{ old('photo') }}"
                                        type="file" data-allowed-file-extensions="png jpg jpeg"
                                        data-max-file-size="2024K" />
                                    <p>{{ __('Max Size: 2MB, Allowed Format: png, jpg, jpeg') }}</p>
                                </div>
                                @if ($errors->has('photo'))
                                    <div class="error ambitious-red">{{ $errors->first('photo') }}</div>
                                @endif
                            </div>

                            <div class="row col-12 ml-3 p-0">
                                <input type="submit" value="{{ __('Submit') }}"
                                    class="btn btn-outline btn-info btn-lg" />
                                <a href="{{ route('users.index') }}"
                                    class="btn btn-outline btn-warning btn-lg ml-2">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script src="{{ asset('assets/js/custom/users/create.js') }}"></script>
@endpush

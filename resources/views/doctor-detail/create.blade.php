@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
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
                            <a href="{{ route('doctor-details.index') }}">@lang('Employee')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Employee')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Employee')</h3>
                </div>
                <div class="card-body">
                    <form id="departmentForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('doctor-details.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row col-12 p-0 m-0">
                            <div class="col-md-3">
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
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone">@lang('Phone')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="number" id="phone" name="phone" value="{{ old('phone') }}"
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
                            <div class="col-xs-12 col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label for="email" class="email-label">
                                        @lang('Email') <b class="ambitious-crimson">*</b>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                        </div>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="@lang('example@gmail.com')" required>
                                        <input style="position: absolute;top: -25px;left: 55px;" type="checkbox"
                                            class="form-check-input email-checkbox" id="noEmailCheckbox">
                                        <label style="position: absolute;top: -30px;left: 73px;" class="form-check-label"
                                            for="noEmailCheckbox">@lang('No Email')</label>
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_of_birth">@lang('Date of Birth')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                                        </div>
                                        <input type="text" name="date_of_birth" id="date_of_birth"
                                            class="form-control flatpickr @error('date_of_birth') is-invalid @enderror"
                                            placeholder="@lang('Date of Birth')" value="{{ old('date_of_birth') }}">
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
                                            value="{{ old('specialist') }}"
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
                                            value="{{ old('designation') }}"
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
                                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>
                                                @lang('Male')</option>
                                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>
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
                                            value="{{ old('commission') }}"
                                            class="form-control @error('commission') is-invalid @enderror"
                                            placeholder="@lang('Commission')">
                                        @error('commission')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <div class="invalid-feedback" id="js-invalid-feedback" style="display: none;">
                                            Commission must be between 1 and 100.
                                        </div>
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
                                            <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>
                                                @lang('Active')
                                            </option>
                                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                                @lang('Inactive')
                                            </option>
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
                                        placeholder="@lang('Address')">{{ old('address') }}</textarea>
                                    </div>
                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row col-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-3" for="biography">@lang('Biography')</label>
                                    <textarea name="biography" id="biography" class="pt-2 form-control @error('biography') is-invalid @enderror"
                                        rows="2" placeholder="@lang('Biography')">{{ old('biography') }}</textarea>
                                    @error('biography')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                                <div class="col-md-6">
                                    <label for="photo" class="col-md-12 col-form-label">
                                        <h4>{{ __('Photo') }}</h4>
                                    </label>
                                    <div class="col-md-12 m-0 p-0">
                                        <input id="photo" class="dropify" name="photo" type="file"
                                            data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2024K" />
                                        <p>{{ __('Max Size: 1000kb, Allowed Format: png, jpg, jpeg') }}</p>
                                    </div>
                                    @error('photo')
                                        <div class="error ambitious-red">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    
                                    
                                </div>
                            </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 col-form-label"></label>
                                        <div class="col-md-8">
                                            <input type="submit" value="{{ __('Submit') }}"
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
    <script>
        document.getElementById('noEmailCheckbox').addEventListener('change', function() {
            var emailField = document.getElementById('email');
            var phoneField = document.getElementById('phone');

            if (this.checked) {
                emailField.value = 'noemail' + phoneField.value + '@gmail.com';
                emailField.setAttribute('readonly', true);
            } else {
                emailField.value = '';
                emailField.removeAttribute('readonly');
            }
        });
        // FOR COMMISSAION

        document.addEventListener('DOMContentLoaded', function() {
            var commissionInput = document.getElementById('commission');
            var jsInvalidFeedback = document.getElementById('js-invalid-feedback');

            commissionInput.addEventListener('input', function() {
                var value = parseFloat(commissionInput.value);
                if (value > 100 || value < 1 || isNaN(value)) {
                    commissionInput.value = ' ';
                    commissionInput.classList.add('is-invalid');
                    jsInvalidFeedback.style.display = 'block';
                } else {
                    commissionInput.classList.remove('is-invalid');
                    jsInvalidFeedback.style.display = 'none';
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/custom/doctor-detail.js') }}"></script>
@endsection

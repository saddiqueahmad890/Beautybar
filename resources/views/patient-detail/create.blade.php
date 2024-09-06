@extends('layouts.layout')
@section('content')
    <style>
        .email-label {
            display: inline-block;
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
            flex-wrap:nowrap;
        }

        .email-checkbox {
            position: absolute;
            /* right: 10px; */
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3><a href="{{ route('patient-details.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')</a>
                        <span class="pull-right"></span>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-details.index') }}">@lang('Customer')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Add Customer')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Add Customer')</h3>
                </div>
                <div class="card-body">
                    <form id="patientForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('patient-details.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row col-12 p-0 m-0">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="@lang('John Doe')" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="phone">@lang('Phone')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="number" id="phone" name="phone" value="{{ old('phone') }}"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="@lang('03375544887')">
                                        @error('phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
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
                                        <input style="position: absolute;top: -19px;left: 55px;" type="checkbox"
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


                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">

                                <div class="form-group">
                                    <label for="gender">@lang('Gender')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        </div>
                                        <select name="gender" value="{{ old('gender') }}"
                                            class="form-control @error('gender') is-invalid @enderror" id="gender">
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

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="area">@lang('Area')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                        </div>
                                        <input type="text" name="area" value="{{ old('area') }}" id="area"
                                            class="form-control @error('area') is-invalid @enderror" rows="1"
                                            placeholder="@lang('i8 Markaz')" />
                                        @error('area')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="city">@lang('City')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                        </div>
                                        <input type="text" name="city" value="{{ old('city') }}" id="city"
                                            class="form-control @error('city') is-invalid @enderror" rows="1"
                                            placeholder="@lang('Islamabad')" />
                                        @error('city')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xl-6">
                                <div class="form-group">
                                    <label for="address">@lang('Address')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                                        </div>
                                        <input type="text" name="address" value="{{ old('address') }}"
                                            id="address" class="form-control @error('address') is-invalid @enderror"
                                            rows="1" placeholder="@lang('House 35, Street 66, i8 markaz, Islamabad')"{{ old('address') }} />
                                        @error('address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="status">@lang('Status') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                        </div>
                                        <select
                                            class="form-control ambitious-form-loading @error('status') is-invalid @enderror"
                                            required name="status" value="{{ old('status') }}" id="status">
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
                            <input type="hidden" id="password" name="password" value="12345678" required>


                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="other-details">Other Details</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        </div>
                                        <textarea rows="1" type="text" id="other-details" name="other_details" value="{{ old('other_details') }}"
                                            class="form-control fixed-height-textarea" placeholder="Additional details..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xl-3">
                                <div class="form-group">
                                    <label for="enquirysource">@lang('Where did you hear about us?')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                                        </div>
                                        <select name="enquirysource"
                                            class="form-control select2 @error('enquiry') is-invalid @enderror"
                                            id="enquirysource">
                                            <option value="" disabled selected>Select Enquiry Sources</option>
                                            @foreach ($enquirysource as $enquiry)
                                                <option value="{{ $enquiry->id }}"
                                                    {{ old('enquirysource') == $enquiry->id ? 'selected' : '' }}>
                                                    {{ $enquiry->source_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('enquirysource')
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
    </script>
@endsection

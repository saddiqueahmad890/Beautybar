@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2"><a href="{{ route('patient-details.create') }}" class="btn btn-outline btn-info">+
                            @lang('Add Customer')</a>
                        <span class="pull-right"></span>
                    </h3>
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
                        <li class="breadcrumb-item active">@lang('Edit Customer')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" id="record_id" value="{{ $patientDetail->id }}">
    <input type="hidden" id="table_name" value="patient">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Edit Customer ({{ $patientDetail->name }}) </h3>
                </div>
                <div class="card-body">
                    <form id="departmentForm" class="form-material form-horizontal bg-custom"
                        action="{{ route('patient-details.update', $patientDetail) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="row col-12 p-0 m-0">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        </div>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $patientDetail->name) }}"
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
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="phone">@lang('Phone')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" id="phone" name="phone"
                                            value="{{ old('phone', $patientDetail->phone) }}"
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
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="email">@lang('Email') <b class="ambitious-crimson">*</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                        </div>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', $patientDetail->email) }}"
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
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="form-group">
                                    <label for="gender">@lang('Gender')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        </div>
                                        <select name="gender"
                                            class="form-control select2 @error('gender') is-invalid @enderror"
                                            id="gender">
                                            <option value="male" @if (old('gender', $patientDetail->gender) == 'male') selected @endif>
                                                @lang('Male')</option>
                                            <option value="female" @if (old('gender', $patientDetail->gender) == 'female') selected @endif>
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
                                        <input type="text" name="area" id="area"
                                            class="form-control @error('area') is-invalid @enderror"
                                            value="{{ old('area', $patientDetail->patientDetails->area) }}"
                                            placeholder="@lang('Area')" />
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
                                        <input type="text" name="city" id="city"
                                            class="form-control @error('city') is-invalid @enderror"
                                            value="{{ old('city', $patientDetail->patientDetails->city) }}"
                                            placeholder="@lang('City')" />
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
                                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="1"
                                            placeholder="@lang('Address')">{{ old('address', $patientDetail->address) }}</textarea>
                                    
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
                                            class="form-control select2 ambitious-form-loading @error('status') is-invalid @enderror"
                                            required name="status" id="status">
                                            <option value="1" @if (old('status', $patientDetail->status) == '1') selected @endif>
                                                @lang('Active')</option>
                                            <option value="1" @if (old('status', $patientDetail->status) == '0') selected @endif>
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

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="other_details">@lang('Other Details')</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-file"></i></span>
                                        </div>
                                        <textarea name="other_details" id="other_details"
                                            class="form-control @error('other_details') is-invalid @enderror fixed-height-textarea" rows="1"
                                            placeholder="@lang('Additional details...')">{{ old('other_details', $patientDetail->patientDetails->other_details) }}</textarea>
                                        @error('other_details')
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
                        <label for="enquirysource">@lang('Where did you hear about us?')</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                            </div>
                            <select name="enquirysource"
                                class="form-control select2 @error('enquiry') is-invalid @enderror" id="enquirysource">
                                <option value="" disabled> Select Source </option>
                                @foreach ($enquirysource as $enquiry)
                                    <option value="{{ $enquiry->id }}"
                                        @if (old('enquirysource', $patientDetail->patientDetails->enquirysource) == $enquiry->id) selected @endif>
                                        {{ $enquiry->source_name }}</option>
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
                            <input type="submit" value="{{ __('Update') }}" class="btn btn-outline btn-info btn-lg" />
                            <a href="{{ route('patient-details.index') }}"
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
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title">Upload Profile Picture</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="col-md-12">
                        <input id="profile_picture" name="profile_picture" type="file"
                            data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2048K" />
                        <p>{{ __('Max Size: 2048kb, Allowed Format: png, jpg, jpeg') }}</p>
                        <br>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Profile Picture')</th>
                                    <th>@lang('Uploaded By')</th>
                                    <th>@lang('Upload Date')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody id="profilePictureTableBody" class="fileTableBody"></tbody>

                            </tbody>
                        </table>
                    </div>
                    @error('profile_picture')
                        <div class="error ambitious-red">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
        <script>
        var getFilesUrl = "{{ route('get-files', $patientDetail->id) }}";
        var uploadFilesUrl = "{{ route('upload-file') }}";
        var deleteFilesUrl = "{{ route('delete-file') }}";
        var baseUrl = '{{ asset('') }}';
        console.log("This is Base Url", baseUrl);
        console.log('This is getFiles Url:', getFilesUrl);
        console.log('This is Upload File Url', uploadFilesUrl);
        console.log("Delete File Url", deleteFilesUrl);
    </script>
@endsection

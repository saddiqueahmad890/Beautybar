@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('Edit Consultancy Fee')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('consultancey-fees.index') }}">@lang('Consultancy Fee')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Edit Consultancy Fee')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Edit Consultancy Fee')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('consultancey-fees.update', $consultanceyFee->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Consultancy Fee Date') <b class="text-danger">*</b></label>
                                    <input type="text" name="date" id="date"
                                        class="form-control @error('date') is-invalid @enderror"
                                        placeholder="@lang('Consultancy Fee Date')" value="{{ old('date', $consultanceyFee->date, date('d-m-Y')) }}"
                                        required readonly>
                                    @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('User ID')</label>
                                    
                                    <input type="text" name="user_id" class="form-control"
                                        placeholder="@lang('User ID')"
                                        value="{{ old('user_id', $consultanceyFee->user_id ) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>@lang('Description')</th>
                                        <th>@lang('Amount')</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <textarea name="description" class="form-control" rows="1" placeholder="@lang('Description')">{{ old('description', $consultanceyFee->description) }}</textarea>
                                        </td>
                                        <td>
                                            <input type="number" step=".01" name="amount" class="form-control"
                                                placeholder="@lang('Amount')"
                                                value="{{ old('amount', $consultanceyFee->amount) }}" required>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="offset-md-0 col-md-8">
                                        <input type="submit" value="{{ __('Submit') }}"
                                            class="btn btn-outline btn-info btn-lg" />
                                        <a href="{{ route('consultancey-fees.index') }}"
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

@push('footer')
    <script src="{{ asset('assets/js/custom/consultancy.js') }}"></script>
@endpush

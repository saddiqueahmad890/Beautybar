@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('patient-dental-histories.create') }}" class="btn btn-outline btn-info">
                            @lang('Add Patient Dental History')
                        </a>
                    </h3>
                    <h3>
                        <a href="{{ route('patient-dental-histories.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-dental-histories.index') }}">@lang('Patient Dental List')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Show Patient Dental')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">Show Patient ({{ $patient->name }}) Dental History</h3>
                    </div>
                    <div class="card-body">
                        <div id="dentalHistoryRows">
                            <div class="" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h3>Dental History</h3>
                                <div class="row">
                                    @forelse ($patientDentalHistories as $item)
                                        <div class="col-xl-3 p-3">
                                            <div class="form-group m-0">
                                                <label>{{ $item->ddDentalHistory->title ?? "    "}}</label>
                                                <p>{{ $item->comments }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No dental history found for this patient.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('patient-social-histories.create') }}" class="btn btn-outline btn-info">
                            @lang('Add Patient Social History')
                        </a>
                    </h3>
                    <h3>
                        <a href="{{ route('patient-social-histories.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-social-histories.index') }}">@lang('Patient Social List')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Show Patient Social')</li>
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
                        <h3 class="card-title">Show Patient ({{ $patient->name }}) Social History</h3>
                    </div>
                    <div class="card-body">
                        <div id="socialHistoryRows">
                            <div class="" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h3>Social History</h3>
                                <div class="row">
                                    @forelse ($patientSocialHistories as $item)
                                        <div class="col-xl-3 p-3">
                                            <div class="form-group m-0">
                                                <label>{{ $item->ddSocialHistory->title }}</label>
                                                <p>{{ $item->comments }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No social history found for this patient.</p>
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

@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 d-flex">
                    <h3 class="mr-2">
                        <a href="{{ route('patient-drug-histories.create') }}" class="btn btn-outline btn-info">
                            @lang('Add Patient Drug History')
                        </a>
                    </h3>
                    <h3>
                        <a href="{{ route('patient-drug-histories.index') }}" class="btn btn-outline btn-info">
                            <i class="fas fa-eye"></i> @lang('View All')
                        </a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-drug-histories.index') }}">@lang('Patient Drug List')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Show Patient Drug')</li>
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
                        <h3 class="card-title">Show Patient ({{ $patient->name }}) Drug History</h3>
                    </div>
                    <div class="card-body">
                        <div id="drugHistoryRows">
                            <div class="" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h3>Drug History</h3>
                                <div class="row">
                                    @forelse ($patientDrugHistories as $item)
                                        <div class="col-xl-3 p-3">
                                            <div class="form-group m-0">
                                                <label>{{ $item->ddDrugHistory->title }}</label>
                                                <p>{{ $item->comments }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No drug history found for this patient.</p>
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

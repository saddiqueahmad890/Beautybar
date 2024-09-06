@extends('layouts.layout')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <div class="col-sm-6 d-flex">
                        <div id="buttonContainer">
                            <!-- Medical History Buttons -->
                            <div id="medicalHistoryButtons">
                                <h3 class="mr-2">
                                    @if ($patientMedicalHistories->isEmpty())
                                        <!-- If no data, show the + Add button -->
                                        <a href="{{ route('patient-medical-histories.create.from-patient', ['userid' => $patientDetail->id]) }}"
                                            class="btn btn-outline btn-info">+
                                            @lang('Add Customer Medical History')
                                        </a>
                                    @endif
                                </h3>
                            </div>

                            <!-- Drug History Buttons -->
                            <div id="drugHistoryButtons">
                                <h3 class="mr-2">
                                    @if ($patientDrugHistories->isEmpty())
                                        <!-- If no data, show the + Add button -->
                                        <a href="{{ route('patient-drug-histories.create.from-patient', ['userid' => $patientDetail->id]) }}"
                                            class="btn btn-outline btn-info">
                                            + @lang('Add Customer Drug History')
                                        </a>
                                    @endif
                                </h3>
                            </div>

                            <!-- Social History Buttons -->
                            <div id="socialHistoryButtons">
                                <h3 class="mr-2">
                                    @if ($patientSocialHistories->isEmpty())
                                        <!-- If no data, show the + Add button -->
                                        <a href="{{ route('patient-social-histories.create.from-patient', ['userid' => $patientDetail->id]) }}"
                                            class="btn btn-outline btn-info">+
                                            @lang('Add Customer Social History')
                                        </a>
                                    @endif
                                </h3>
                            </div>

                            <!-- Dental History Buttons -->
                            <div id="dentalHistoryButtons">
                                <h3 class="mr-2">
                                    @if ($patientDentalHistories->isEmpty())
                                        <!-- If no data, show the + Add button -->
                                        <a href="{{ route('patient-dental-histories.create.from-patient', ['userid' => $patientDetail->id]) }}"
                                            class="btn btn-outline btn-info">+
                                            @lang('Add Customer Dental History')
                                        </a>
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('patient-details.index') }}">@lang('Customer')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('Customer Info')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Customer history') - {{ $patientDetail->name }}</h3>
                    <div class="profile-pic">
                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/' . $profilePicture) }}"
                            alt="Profile Picture" />
                    </div>
                </div>

                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">@lang('Medical History')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false">@lang('Drug History')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab"
                                aria-controls="messages" aria-selected="false">@lang('Social History')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab"
                                aria-controls="settings" aria-selected="false">@lang('Dental History')</a>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Medical History Tab Content -->
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            @if ($patientMedicalHistories->isNotEmpty())
                                <div class="row">
                                    <div class="col-md-10">
                                        <h3>@lang('Medical History of') {{ $patientDetail->name }}</h3>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="{{ route('patient-medical-histories.edit', ['patient_medical_history' => $patientMedicalHistories->first()->id]) }}"
                                            class="btn btn-info mt-2">@lang('Edit Medical History')</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    @foreach ($patientMedicalHistories as $item)
                                        <div class="col-xl-3 p-3">
                                            <div class="form-group m-0">
                                                <label>{{ $item->ddMedicalHistory->title }}</label>
                                                <p>{{ $item->comments }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>@lang('No medical history found.')</p>
                            @endif
                        </div>

                        <!-- Drug History Tab Content -->
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            @if ($patientDrugHistories->isNotEmpty())
                                <div class="row">
                                    <div class="col-md-10">
                                        <h3>@lang('Drug History of') {{ $patientDetail->name }}</h3>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="{{ route('patient-drug-histories.edit', ['patient_drug_history' => $patientDrugHistories->first()->id]) }}"
                                            class="btn btn-info mt-2">@lang('Edit Drug History')</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    @foreach ($patientDrugHistories as $item)
                                        <div class="col-xl-3 p-3">
                                            <div class="form-group m-0">
                                                <label>{{ $item->ddDrugHistory->title }}</label>
                                                <p>{{ $item->comments }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>@lang('No drug history found.')</p>
                            @endif
                        </div>

                        <!-- Social History Tab Content -->
                        <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                            @if ($patientSocialHistories->isNotEmpty())
                                <div class="row">
                                    <div class="col-md-10">
                                        <h3>@lang('Social History of') {{ $patientDetail->name }}</h3>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="{{ route('patient-social-histories.edit', ['patient_social_history' => $patientSocialHistories->first()->id]) }}"
                                            class="btn btn-info mt-2">@lang('Edit Social History')</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    @foreach ($patientSocialHistories as $item)
                                        <div class="col-xl-3 p-3">
                                            <div class="form-group m-0">
                                                <label>{{ $item->ddSocialHistory->title }}</label>
                                                <p>{{ $item->comments }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>@lang('No social history found.')</p>
                            @endif
                        </div>

                        <!-- Dental History Tab Content -->
                        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                            @if ($patientDentalHistories->isNotEmpty())
                                <div class="row">
                                    <div class="col-md-10">
                                        <h3>@lang('Dental History of') {{ $patientDetail->name }}</h3>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="{{ route('patient-dental-histories.edit', ['patient_dental_history' => $patientDentalHistories->first()->id]) }}"
                                            class="btn btn-info mt-2">@lang('Edit Dental History')</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    @foreach ($patientDentalHistories as $item)
                                        <div class="col-xl-3 p-3">
                                            <div class="form-group m-0">
                                                <label>{{ $item->ddDentalHistory->title }}</label>
                                                <p>{{ $item->comments }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>@lang('No dental history found.')</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Section -->
    <script>
        $(document).ready(function() {
            $('#myTab a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');

                var activeTab = $(this).attr('id');
                switch (activeTab) {
                    case 'home-tab':
                        $('#medicalHistoryButtons').show();
                        $('#drugHistoryButtons, #socialHistoryButtons, #dentalHistoryButtons').hide();
                        break;
                    case 'profile-tab':
                        $('#drugHistoryButtons').show();
                        $('#medicalHistoryButtons, #socialHistoryButtons, #dentalHistoryButtons').hide();
                        break;
                    case 'messages-tab':
                        $('#socialHistoryButtons').show();
                        $('#medicalHistoryButtons, #drugHistoryButtons, #dentalHistoryButtons').hide();
                        break;
                    case 'settings-tab':
                        $('#dentalHistoryButtons').show();
                        $('#medicalHistoryButtons, #drugHistoryButtons, #socialHistoryButtons').hide();
                        break;
                }
            });

            // Trigger click on the active tab to show the correct buttons initially
            $('#myTab a.active').trigger('click');
        });
    </script>
@endsection

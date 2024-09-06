@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Consume Records')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Consumption Records')</h3>
                    <div class="card-tools">
                        <a class="btn btn-primary float-right" target="_blank" href="{{ route('consume.index') }}?export=1">
                            <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                        </a>


                        <button class="btn btn-default" data-toggle="collapse" href="#filter">
                            <i class="fas fa-filter"></i> @lang('Filter')
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->isFilterActive) show @endif">
                        <div class="card-body border">
                            <form action="{{ route('consume.index') }}" method="get" role="form" autocomplete="off">
                                <input type="hidden" name="isFilterActive" value="true">
                                <!-- Add this hidden input for the type parameter -->
                                <input type="hidden" name="type" value="{{ request()->query('type') }}">

                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Item')</label>
                                            <input type="text" name="item" class="form-control"
                                                placeholder="@lang('Item')" value="{{ old('item', request()->item) }}">
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Type')</label>
                                            <select name="type" class="form-control">
                                                <option value="">@lang('Select Type')</option>
                                                <option value="sln_items" @if (request()->type === 'sln_items') selected @endif>
                                                    @lang('Salon Items')</option>
                                                <option value="ofs_items" @if (request()->type === 'ofs_items') selected @endif>
                                                    @lang('Office Items')</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Approval Date')</label>
                                            <input type="date" name="approval_date" class="form-control"
                                                placeholder="@lang('Approval Date')"
                                                value="{{ old('approval_date', request()->approval_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>@lang('Date')</label>
                                            <input type="date" name="date" class="form-control"
                                                placeholder="@lang('Date')" value="{{ old('date', request()->date) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if (request()->isFilterActive)
                                            <a href="{{ route('consume.index', ['type' => request()->query('type')]) }}"
                                                class="btn btn-secondary">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>@lang('Items')</th>
                                <th>@lang('Stock Quantity')</th>
                                <th>@lang('Receptionist  Qty')</th>
                                <th>@lang('Consumed Qty')</th>
                                <th>@lang('Approval Date')</th>
                                <th>@lang('Date')</th>
                                @if (request()->query('type') === 'ofs_items')
                                    <th>@lang('Description')</th>
                                @endif
                                @if (request()->query('type') === 'ofs_items' || request()->query('type') === 'sln_items')
                                    <th>@lang('Approved Status')</th>
                                @endif


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr>
                                    <td>{{ $record->inventory->item->title ?? '' }}</td>
                                    <td>{{ $record->inventory->quantity }}</td>
                                    <td>{{ $record->consumed_quantity }}</td>
                                    <td>{{ $record->approved_quantity }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($record->approval_date)->format('Y-m-d') }}
                                        ({{ \Carbon\Carbon::parse($record->approval_date)->format('H:i:s') }})
                                    </td>

                                    <td>{{ $record->date }}</td>
                                    @if (request()->query('type') == 'ofs_items')
                                        <td>{{ $record->description }}</td>
                                    @endif
                                    @if (request()->query('type') === 'ofs_items' || request()->query('type') === 'sln_items')
                                        <td>
                                            @if ($record->approved === 'no')
                                                <form action="{{ route('consumption-records.updateApproval', $record) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('Are you sure you want to approve this record?');">
                                                        <i class="fas fa-check fa-xs"></i>
                                                    </button>
                                                </form>
                                            @else
                                                Approved
                                            @endif
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $records->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('layouts.delete_modal')
@endsection

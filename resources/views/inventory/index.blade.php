@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>
                        <a href="{{ route('inventories.create', ['type' => request()->query('type')]) }}"
                            class="btn btn-outline btn-info">
                            + @lang('Add Inventory')
                        </a>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Inventory List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Inventory List')</h3>
                    <div class="card-tools">
                        <a class="btn btn-primary" target="_blank" href="{{ route('inventories.index') }}?export=1">
                            <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                        </a>
                        <button class="btn btn-default" data-toggle="collapse" href="#filter">
                            <i class="fas fa-filter"></i> @lang('Filter')
                        </button>
                    </div>
                </div>


                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->has('isFilterActive')) show @endif">
                        <div class="card-body border">
                            <form action="{{ route('inventories.index') }}" method="get" autocomplete="off">
                                <input type="hidden" name="type" value="{{ request()->query('type') }}">
                                <input type="hidden" name="isFilterActive" value="true">

                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>@lang('Supplier')</label>
                                            <input type="text" name="supplier" class="form-control"
                                                value="{{ request()->input('supplier') }}"
                                                placeholder="@lang('Supplier')">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>@lang('Category')</label>
                                            <input type="text" name="category_title" class="form-control"
                                                value="{{ request()->input('category_title') }}"
                                                placeholder="@lang('Category')">
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>@lang('Items')</label>
                                            <input type="text" name="item_title" class="form-control"
                                                value="{{ request()->input('item_title') }}"
                                                placeholder="@lang('Item')">
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>@lang('Start Date')</label>
                                            <input type="text" name="start_date" id="start_date"
                                                class="form-control flatpickr" placeholder="@lang('Start Date')"
                                                value="{{ old('start_date', request()->start_date) }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>@lang('End Date')</label>
                                            <input type="text" name="end_date" id="end_date"
                                                class="form-control flatpickr" placeholder="@lang('End Date')"
                                                value="{{ old('end_date', request()->end_date) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if (request()->has('isFilterActive'))
                                            <a href="{{ route('inventories.index') }}?type={{ request()->query('type') }}"
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
                                <th>@lang('Supplier')</th>
                                <th>@lang('Category')</th>
                                <th>@lang('Item')</th>
                                <th>@lang('Qty in Stock')</th>
                                @if (request()->query('type') == 'sln_items')
                                    <th>@lang('Qty at Receptionist')</th>
                                @endif
                                <th>@lang('Unit Cost')</th>
                                @if (request()->query('type') == 'sln_items')
                                    <th>@lang('Unit sale')</th>
                                @endif
                                <th>@lang('Date')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventories as $inventory)
                                <tr>
                                    <td>{{ $inventory->supplier ?? '-' }}</td>
                                    <td><span style="white-space: nowrap;">{{ $inventory->category->title ?? '' }}</span>
                                    </td>
                                    <td>{{ $inventory->item->title ?? ' ' }}</td>
                                    <td>{{ $inventory->quantity }}</td>
                                    @if (request()->query('type') == 'sln_items')
                                        <td>{{ $inventory->usages->sum('consumed_quantity') + $inventory->invoiceitem->sum('quantity') }}
                                        </td>
                                    @endif
                                    <td>{{ $inventory->unit_cost }} PKR</td>
                                    @if (request()->query('type') == 'sln_items')
                                        <td>{{ $inventory->unit_sale }} PKR</td>
                                    @endif
                                    <td>{{ $inventory->created_at->format('d-m-Y') }}</td>
                                    <td class="responsive-width">
                                        @if (request()->query('type') == 'ofs_items')
                                            <a href="{{ route('inventories.show', $inventory) }}?type={{ request()->query('type') }}"
                                                class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                                data-toggle="tooltip" title="@lang('View')">
                                                <i class="fa fa-eye ambitious-padding-btn"></i>
                                            </a>
                                        @endif

                                        @if (request()->query('type') == 'sln_items')
                                            <a href="{{ url('invoices/create?type=sale&inventory_id=' . $inventory->id) }}"
                                                class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip"
                                                title="@lang('Sale')">
                                                <i class="fa fa-shopping-cart ambitious-padding-btn"></i>
                                            </a>
                                        @endif

                                        <button type="button" class="btn btn-warning btn-outline btn-circle btn-lg"
                                            data-toggle="modal" data-target="#consumedModal"
                                            onclick="openConsumedModal('{{ $inventory->item->title }}', '{{ route('consumed.update', ['inventory' => $inventory->id, 'type' => request()->query('type')]) }}')"
                                            title="@lang('Consumed')">
                                            <i class="fa fa-check-circle ambitious-padding-btn"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $inventories->links() }}

                    <!-- Consumed Modal -->
                    <div class="modal fade" id="consumedModal" tabindex="-1" role="dialog"
                        aria-labelledby="consumedModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="consumedModalLabel">@lang('Consume Item')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="consumedForm" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="item_name">@lang('Item Name')</label>
                                            <input type="text" id="item_name" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="date">@lang('Date')</label>
                                            <input type="date" name="date" id="date" class="form-control"
                                                required value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" readonly>
                                        </div>
                                        <input type="hidden" value="{{ $type }}" name="type"
                                            id="type">
                                        <div class="form-group">
                                            <label for="quantity">@lang('Quantity')</label>
                                            <input type="number" name="quantity" id="quantity" class="form-control"
                                                required>
                                        </div>
                                        @if (request()->query('type') == 'ofs_items')
                                            <div class="form-group">
                                                <label for="description">@lang('Description')<b
                                                        class="text-danger">*</b></label>
                                                <input type="text" name="description" id="description"
                                                    class="form-control" required>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">@lang('Close')</button>
                                        <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Consumed Modal -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function openConsumedModal(itemName, actionUrl) {
            // Set the item name in the modal
            document.getElementById('item_name').value = itemName;

            // Set the form action to the route
            document.getElementById('consumedForm').action = actionUrl;
        }
    </script>

    @include('layouts.delete_modal')
@endsection

@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- @can('item-create') --}}
                    <h3>
                        <a href="{{ route('items.create') }}?type={{ request()->query('type') }}"
                            class="btn btn-outline btn-info">
                            + @lang('Add Item')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    {{-- @endcan --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('Item List')</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">@lang('Item List')</h3>
                    <div class="card-tools">
                        <button class="btn btn-default" data-toggle="collapse" href="#filter">
                            <i class="fas fa-filter"></i> @lang('Filter')
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->has('isFilterActive')) show @endif">
                        <div class="card-body border">

                            <form action="{{ route('items.index') }}" method="get">
                                <input type="hidden" name="type" value="{{ request()->query('type') }}">
                                <input type="hidden" name="isFilterActive" value="true">
                                <div class="row">
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Category')</label>
                                            <input type="text" name="category" class="form-control"
                                                value="{{ request()->input('category') }}" placeholder="@lang('Category')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Items')</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ request()->input('title') }}" placeholder="@lang('items')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Description')</label>
                                            <input type="text" name="description" class="form-control"
                                                value="{{ request()->input('description') }}"
                                                placeholder="@lang('Description')">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 mt-sm-4 pt-sm-2">
                                    <button type="submit" class="btn btn-info mt-2">@lang('Submit')</button>
                                    @if (request()->has('isFilterActive'))
                                        <a href="{{ route('items.index') }}?type={{ request()->query('type') }}"
                                            class="btn btn-secondary mt-2">@lang('Clear')</a>
                                    @endif
                                </div>
                            </form>


                        </div>
                    </div>
                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>

                                <th>@lang('Categories')</th>
                                <th>@lang('Items')</th>
                                <th>@lang('Description')</th>

                                <th>@lang('Action')</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>


                                    <td><span
                                            style="text-wrap:nowrap;">{{ $item->category ? $item->category->title : '-' }}</span>
                                    </td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->description }}</td>

                                    <td class="responsive-width">
                                        <a href="{{ route('items.show', $item) }}?type={{ request()->query('type') }}"
                                            class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="tooltip" title="@lang('Edit')"><i
                                                class="fa fa-eye ambitious-padding-btn"></i></a>

                                        {{-- @can('item-update') --}}
                                        <a href="{{ route('items.edit', $item) }}?type={{ request()->query('type') }}"
                                            class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="tooltip" title="@lang('Edit')"><i
                                                class="fa fa-edit ambitious-padding-btn"></i></a>
                                        {{-- @endcan --}}
                                        {{-- @can('item-delete') --}}
                                        <a href="#" data-href="{{ route('items.destroy', $item) }}"
                                            class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i
                                                class="fa fa-trash ambitious-padding-btn"></i></a>
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $items->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.delete_modal')
@endsection

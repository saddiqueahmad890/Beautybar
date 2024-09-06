@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- @can('category-create') --}}
                      @if (request()->query('type') == 'ofs_items')
                    <h3>
                        <a href="{{ route('categories.create', ['type' => $type]) }}" class="btn btn-outline btn-info">
                            + @lang('Add office Category')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    @else
                     <h3>
                        <a href="{{ route('categories.create', ['type' => $type]) }}" class="btn btn-outline btn-info">
                            + @lang('Add salon Category')
                        </a>
                        <span class="pull-right"></span>
                    </h3>
                    @endif
                    {{-- @endcan --}}
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                @if (request()->query('type') == 'ofs_items')
                     <li class="breadcrumb-item active">@lang('Office Category List')</li>
                     @else
                     <li class="breadcrumb-item active">@lang('Salon Category List')</li>
                     @endif

                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                @if (request()->query('type') == 'ofs_items')
                    <h3 class="card-title">@lang('Office Category List')</h3>
                    @else
                    <h3 class="card-title">@lang('Salon Category List')</h3>
                    @endif
                    <div class="card-tools">
                        <button class="btn btn-default" data-toggle="collapse" href="#filter">
                            <i class="fas fa-filter"></i> @lang('Filter')
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="filter" class="collapse @if (request()->has('isFilterActive')) show @endif">
                        <div class="card-body border">
                        <form action="{{ route('categories.index') }}?type={{ request()->query('type') }}" method="get">

                                <input type="hidden" name="type" value="{{ request()->query('type') }}">
                                <input type="hidden" name="isFilterActive" value="true">

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>@lang('Category')</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ request()->input('title') }}" placeholder="@lang('Category')">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-2 mt-sm-4">
                                        <button type="submit" class="btn btn-info mt-sm-3">@lang('Submit')</button>
                                        @if (request()->has('isFilterActive'))
                                            <a href="{{ route('categories.index') }}?type={{ request()->query('type') }}"
                                                class="btn btn-secondary mt-3">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>

                                <th>@lang('Category')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>

                                    <td><span style="text-wrap:nowrap;">{{ $category->title }}</span></td>
                                    <td>{{ $category->description }}</td>

                                  

                                    <td class="responsive-width">
                                        <a href="{{ route('categories.show', $category) }}?type={{ request()->query('type') }}"
                                            class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="tooltip" title="@lang('Edit')"><i
                                                class="fa fa-eye ambitious-padding-btn"></i></a>
                                        {{-- @endcan --}}
                                        {{-- @can('category-update') --}}
                                        <a href="{{ route('categories.edit', $category) }}?type={{ request()->query('type') }}"
                                            class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="tooltip" title="@lang('Edit')"><i
                                                class="fa fa-edit ambitious-padding-btn"></i></a>
                                        {{-- @endcan --}}
                                        {{-- @can('category-delete') --}}
                                        <a href="#" data-href="{{ route('categories.destroy', $category) }}"
                                            class="responsive-width-item btn btn-info btn-outline btn-circle btn-lg"
                                            data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i
                                                class="fa fa-trash ambitious-padding-btn"></i></a>
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $categories->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.delete_modal')
@endsection

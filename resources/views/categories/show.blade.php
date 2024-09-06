@extends('layouts.layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">

                    @if (request()->query('type') == 'ofs_items')
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a
                                    href="{{ route('categories.index') }}?type={{ request()->query('type') }}">@lang('Office Category')</a>
                            </li>
                            <li class="breadcrumb-item active">@lang('Office Category Info')</li>
                        </ol>
                    @else
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a
                                    href="{{ route('categories.index') }}?type={{ request()->query('type') }}">@lang('Salon Category')</a>
                            </li>
                            <li class="breadcrumb-item active">@lang('Salon Category Info')</li>
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="card">
        <div class="card-header bg-info">
            @if (request()->query('type') == 'ofs_items')
                <h3 class="card-title">@lang('Office Category Info')</h3>
            @else
                <h3 class="card-title">@lang('Office Category Info')</h3>
            @endif

            <div class="card-tools">
                <a href="{{ route('categories.edit', $category) }}?type={{ request()->query('type') }}"
                    class="btn btn-info">@lang('Edit')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row bg-custom col-12">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="title">@lang('Title')</label>
                        <p>{{ $category->title }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="description">@lang('Description')</label>
                        <p>{{ $category->description }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">@lang('Type')</label>
                        <p>{{ $category->type }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">@lang('Status')</label>
                        <p>
                            @if ($category->status == '1')
                                Active
                            @else
                                Inactive
                            @endif
                            </td>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

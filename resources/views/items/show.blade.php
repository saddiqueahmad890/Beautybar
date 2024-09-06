@extends('layouts.layout')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>@lang('items Information')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('items.index') }}?type={{ request()->query('type')}}">@lang('items List')</a>
                    </li>
                    <li class="breadcrumb-item active">@lang('items Information')</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">@lang('items Information')</h3>
                <div class="card-tools">
                <a href="{{ route('items.edit', $item) }}?type={{ request()->query('type')}}" class="btn btn-info">@lang('Edit')</a>
            </div>
            </div>
            <div class="card-body">
                <div class="row bg-custom col-12">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="title">@lang('Title')</label>
                            <p>{{ $item->title }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="description">@lang('Description')</label>
                            <p>{{$item->description }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="item_date">@lang('Date')</label>
                            <p>{{$item->item_date }}</p>
                        </div>
                    </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

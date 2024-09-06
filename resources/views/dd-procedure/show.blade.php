@extends('layouts.layout')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>@lang('Services Category Information')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dd-procedure-categories.index') }}">@lang('Services History')</a>
                    </li>
                    <li class="breadcrumb-item active">@lang('Services Information')</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">@lang('Services Information')</h3>
                <div class="card-tools">
                <a href="{{ route('dd-procedures.edit',$ddProcedure) }}" class="btn btn-info">@lang('Edit')</a>
            </div>
            </div>
            <div class="card-body">
                <div class="row bg-custom col-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="title">@lang('Title')</label>
                            <p>{{  $ddProcedure->title }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="description">@lang('Description')</label>
                            <p>{{$ddProcedure->description}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="price">@lang('Price')</label>
                            <p>{{ $ddProcedure->price}}</p> 
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="info2">@lang('Services Category')</label>
                            <p>{{$ddProcedure->ddprocedurecategory->title}}</p> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

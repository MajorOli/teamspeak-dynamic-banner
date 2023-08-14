@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="col-md-12 card-group">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $instances_count }} {{ \Illuminate\Support\Str::plural("Instance", $instances_count) }}</h5>
                    <p class="card-text">An instance can be seen as data source for the banners.</p>
                    @can('view instances')
                    <a href="{{ route('instances') }}" class="btn btn-primary">Instances</a>
                    @endcan
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $templates_count }} {{ \Illuminate\Support\Str::plural("Template", $templates_count) }}</h5>
                    <p class="card-text">A template defines the design of your banner.</p>
                    @can('view templates')
                    <a href="{{ route('templates') }}" class="btn btn-primary">Templates</a>
                    @endcan
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <div class="card-body">
                <h5 class="card-title">{{ $banners_count }} {{ \Illuminate\Support\Str::plural("Banner", $banners_count) }}</h5>
                    <p class="card-text">The acual dynamic banner configurations, which use your instances and templates.</p>
                    @can('view banners')
                    <a href="{{ route('banners') }}" class="btn btn-primary">Banners</a>
                    @endcan
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <div class="card-body">
                <h5 class="card-title">{{ $banner_configurations_count }} {{ \Illuminate\Support\Str::plural("Banner Configuration", $banner_configurations_count) }}</h5>
                    <p class="card-text">The amount of configured texts, which get dynamically updated on your templates.</p>
                    @can('view banners')
                    <a href="{{ route('banners') }}" class="btn btn-primary">Banners</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

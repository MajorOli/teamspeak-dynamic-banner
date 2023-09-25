@extends('layout')

@section('site_title')
    Login
@endsection

@section('content')
    <div class="container my-auto">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-8 my-auto">
                        <h1 class="fw-bold fs-3 ms-3">Login</h1>
                    </div>
                    <div class="col-lg-4">
                        <img class="card-img pe-3" src="{{asset('img/design/lock.png')}}" alt="Logo" oncontextmenu="return false">
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0">
                    <div class="card-body">
                        @include('inc.standard-alerts')
                        <form method="post" action="{{ Route('login') }}">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label fw-bold" for="email">{{ __('Email Address') }}:</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email') }}" aria-describedby="emailFeedback" placeholder="john.doe@example.de">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold" for="password">{{ __('Password') }}:</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="password" id="password" name="password" placeholder="your password" aria-describedby="passwordFeedback">
                            </div>
                            <div class="mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12 d-grid">
                                    <button class="btn btn-primary">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

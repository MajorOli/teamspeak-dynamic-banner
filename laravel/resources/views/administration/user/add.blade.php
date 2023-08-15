@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __("Add a new user.") }}
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('administration.user.create') }}" class="row g-3 needs-validation" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="validationName" class="form-label">Name</label>
                            <input class="form-control" id="validationName" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. MyNickname" aria-describedby="nameHelp" required>
                            <div id="nameHelp" class="form-text">The users display name.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("Please provide a valid name.") }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="validationEmail" class="form-label">Email</label>
                            <input class="form-control" id="validationEmail" type="email" name="email" value="{{ old('email') }}" placeholder="e.g. max@example.com" aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text">The users email address.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("Please provide a valid email.") }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="validationRoles" class="form-label">Roles</label>
                            <select class="form-control selectpicker" id="validationRoles" multiple data-live-search="true" name="roles[]" aria-describedby="rolesHelp">
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <div id="rolesHelp" class="form-text">The roles (and thus permissions), which the user should get.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("Please select at least one role.") }}</div>
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('administration.users') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>

                    <script>
                        // Example starter JavaScript for disabling form submissions if there are invalid fields
                        (function () {
                        'use strict'

                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        var forms = document.querySelectorAll('.needs-validation')

                        // Loop over them and prevent submission
                        Array.prototype.slice.call(forms)
                            .forEach(function (form) {
                            form.addEventListener('submit', function (event) {
                                if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                                }

                                form.classList.add('was-validated')
                            }, false)
                            })
                        })()
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

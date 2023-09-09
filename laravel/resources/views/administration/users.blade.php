@extends('layout')

@section('site_title')
    Administration Users
@endsection

@section('nav_link_active_user')
    active
@endsection

@section('dataTables_script')
    <script>
        $(document).ready( function () {
            $('#roles').DataTable({
                "oLanguage": {
                    "sLengthMenu": "_MENU_",
                },
                columnDefs:[
                    {
                        orderable: false,
                        targets: 4,
                    }
                ],
            });
        } );
    </script>
@endsection

@section('content')
<div class="container mt-3">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="fw-bold fs-3">User Administration</h1>
        </div>
    </div>
    <hr>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <button type="button" class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#newUser">
                New User
            </button>
        </div>
    </div>
    <hr>
</div>
<div class="container mt-3">
    @include('inc.standard-alerts')
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped" id="roles">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">E-Mail</th>
                    <th scope="col">Roles</th>
                    <th scope="col">Registered since</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="col-lg-2">
                        {{ $user->name }}
                    </td>
                    <td class="col-lg-2">
                        {{ $user->email }}
                    </td>
                    <td class="col-lg-4">
                        @foreach($user->getRoleNames() as $role)
                            @switch(true)
                                @case($role == 'Super Admin')
                                    <span class="badge text-bg-danger">{{$role}}</span>
                                @break
                                @case(preg_match('/Admin/i', $role))
                                    <span class="badge text-bg-warning">{{$role}}</span>
                                @break
                                @default
                                    <span class="badge text-bg-primary">{{$role}}</span>
                            @endswitch
                        @endforeach
                    </td>
                    <td class="col-lg-2">
                        {{date('d.m.Y',strtotime($user->created_at))}}
                    </td>
                    <td class="col-lg-2">
                        <a href="#roleRolesAndPermission-{{$user->id}}" data-bs-toggle="modal" data-bs-target="#roleRolesAndPermission-{{$user->id}}"><i class="fa-solid fa-key text-primary fa-lg me-1"></i></a>
                        <a href="#editUser-{{$user->id}}" data-bs-toggle="modal" data-bs-target="#editUser-{{$user->id}}"><i class="fa-solid fa-pencil text-primary fa-lg me-1"></i></a>
                        <a href="#"><i class="fa fa-trash text-danger fa-lg me-1"></i></a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-12">
            <p>
                <span class="fw-bold">Legende:</span>
                <span class="badge text-bg-danger">Super Admin</span>
                <span class="badge text-bg-warning">Area Admin</span>
                <span class="badge text-bg-primary">Limited Role</span>
            </p>
        </div>
    </div>
</div>

@foreach($users as $userEdit)
    @include('modals.administration.modal-edit')
@endforeach

@foreach($users as $userViewRoles)
    @include('modals.administration.modal-view-user-roles')
@endforeach

@include('modals.administration.modal-add')

@endsection

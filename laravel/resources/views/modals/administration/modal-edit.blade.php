<div class="modal fade" id="editUser-{{$userEdit->id}}" tabindex="-1" aria-labelledby="editUser-{{$userEdit->id}}-Label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="editUser-{{$userEdit->id}}-Label">Edit user <code>{{$userEdit->name}}</code></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('administration.user.update', ['user_id' => $userEdit->id]) }}" class="row g-3 needs-validation" novalidate>
                @method('patch')
                @csrf
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="validationName" class="form-label fw-bold">Nickname</label>
                            <input type="text" class="form-control" id="validationName"
                                   name="name" value="{{ old('name', $userEdit->name) }}"
                                   aria-describedby="validationNameHelp validationNameFeedback-{{$userEdit->id}}"
                                   placeholder="e.g. MyNickname" required>
                            <div id="validationNameHelp" class="form-text">The users display name.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div id="validationNameFeedback-{{$userEdit->id}}" class="invalid-feedback">{{ __("Please provide a valid name.") }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="validationEmail" class="form-label fw-bold">E-Mail</label>
                            <input type="email" class="form-control" id="validationEmail"
                                   name="email" value="{{ old('email', $userEdit->email) }}"
                                   aria-describedby="validationEmailHelp validationEmailFeedback-{{$userEdit->id}}"
                                   placeholder="e.g. max@example.com" required>
                            <div id="validationEmailHelp" class="form-text">The users email address.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div id="validationEmailFeedback-{{$userEdit->id}}" class="invalid-feedback">{{ __("Please provide a valid email.") }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="validationRoles" class="form-label fw-bold">Roles</label>
                            <select class="form-select" multiple id="validationRoles" name="roles[]" aria-describedby="validationRolesHelp validationRolesFeedback-{{$userEdit->id}}">
                                @foreach ($roles as $role)
                                    @if ($role->id == $user->hasRole($role->id))
                                        <option value="{{ $role->id }}" selected>{{ $role->name }}</option>
                                    @else
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="validationRolesHelp" class="form-text">The roles (and thus permissions), which the user should get.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div id="validationRolesFeedback-{{$userEdit->id}}" class="invalid-feedback">{{ __("Please select at least one role.") }}</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
            @include('inc.form-validation')
        </div>
    </div>
</div>
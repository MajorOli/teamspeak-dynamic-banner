@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __("Edit the instance.") }}

                    <form method="POST" action="{{ route('instance.delete', ['instance_id' => $instance->id]) }}">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
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

                    <form method="POST" action="{{ route('instance.update', ['instance_id' => $instance->id]) }}" class="row g-3 needs-validation" novalidate>
                        @method('patch')
                        @csrf

                        <div class="mb-3">
                            <label for="validationVirtualserverName" class="form-label">Virtualserver Name</label>
                            <input class="form-control" id="validationVirtualserverName" type="text" value="{{ $instance->virtualserver_name }}" aria-describedby="virtualserverNameHelp" disabled>
                            <div id="virtualserverNameHelp" class="form-text">The name of your virtualserver (auto-detected).</div>
                        </div>

                        <div class="mb-3">
                            <label for="validationHost" class="form-label">Host</label>
                            <input class="form-control" id="validationHost" type="text" name="host" value="{{ old('host', $instance->host) }}" placeholder="e.g. my.teamspeak.local or 192.168.2.87" aria-describedby="hostHelp" required>
                            <div id="hostHelp" class="form-text">The hostname, domain or IP address of your TeamSpeak server.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("Please provide a valid domain or IP address.") }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="validationVoicePort" class="form-label">Voice Port</label>
                            <input class="form-control" id="validationVoicePort" type="number" name="voice_port" value="{{ old('voice_port', $instance->voice_port) }}" min="1" max="65535" step="1" aria-describedby="voicePortHelp" required>
                            <div id="voicePortHelp" class="form-text">The Voice port of the TeamSpeak server to connect at.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("Please provide a valid port.") }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="validationServerQueryPort" class="form-label">ServerQuery Port</label>
                            <input class="form-control" id="validationServerQueryPort" type="number" name="serverquery_port" value="{{ old('serverquery_port', $instance->serverquery_port) }}" min="1" step="1" max="65535" aria-describedby="serverQueryPortHelp" required>
                            <div id="serverQueryPortHelp" class="form-text">The ServerQuery port of the TeamSpeak server for executing commands and gathering data.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("Please provide a valid port.") }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="validationServerQueryUsername" class="form-label">ServerQuery Username</label>
                            <input class="form-control" id="validationServerQueryUsername" type="text" name="serverquery_username" value="{{ old('serverquery_username', $instance->serverquery_username) }}" placeholder="e.g. serveradmin" aria-describedby="serverQueryUsernameHelp" required>
                            <div id="serverQueryUsernameHelp" class="form-text">The ServerQuery username for the authentication.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("Please provide a valid username.") }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="validationServerQueryPassword" class="form-label">ServerQuery Password</label>
                            <input class="form-control" id="validationServerQueryPassword" type="password" name="serverquery_password" value="{{ old('serverquery_password', $instance->serverquery_password) }}" aria-describedby="serverQueryPasswordHelp" required>
                            <div id="serverQueryPasswordHelp" class="form-text">The password of the previous defined ServerQuery username.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("Please provide a valid password.") }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="validationClientNickname" class="form-label">Client Nickname</label>
                            <input class="form-control" id="validationClientNickname" type="text" maxlength="30" name="client_nickname" value="{{ old('client_nickname', $instance->client_nickname) }}" aria-describedby="clientNicknameHelp" required>
                            <div id="clientNicknameHelp" class="form-text">How this client should be named on your TeamSpeak server. (Maximum length: 30 characters)</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("Please provide a valid nickname.") }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="validationDefaultChannelId" class="form-label">Default Channel</label>
                            <select class="form-select" name="default_channel_id" id="validationDefaultChannelId" aria-describedby="defaultChannelIdHelp">
                                <option value="" selected>Default Channel</option>
                                @foreach ($channel_list as $channel)
                                @if (old('default_channel_id', $instance->default_channel_id) == $channel->cid) "selected"
                                <option value="{{ $channel->cid }}" selected>{{ $channel->channel_name }}</option>
                                @else
                                <option value="{{ $channel->cid }}">{{ $channel->channel_name }}</option>
                                @endif
                                @endforeach
                            </select>
                            <div id="defaultChannelIdHelp" class="form-text">The default channel to which the client should connect / switch on your TeamSpeak server.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("Please provide a valid channel (ID).") }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="validationAutostart" class="form-check-label">Enable autostart</label>
                            @if (old('autostart_enabled', $instance->autostart_enabled))
                            <input class="form-check-input" id="validationAutostart" type="checkbox" name="autostart_enabled" aria-describedby="autostartHelp" checked>
                            @else
                            <input class="form-check-input" id="validationAutostart" type="checkbox" name="autostart_enabled" aria-describedby="autostartHelp">
                            @endif
                            <div id="autostartHelp" class="form-text">When enabled, the application automatically starts the bot instance after up to 5 minutes, if it should not run yet.</div>
                            <div class="valid-feedback">{{ __("Looks good!") }}</div>
                            <div class="invalid-feedback">{{ __("You can only enable or disable this checkbox.") }}</div>
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('instances') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                    <script>
                        // Example starter JavaScript for disabling form submissions if there are invalid fields
                        (function () {
                        'use strict'

                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        let forms = document.querySelectorAll('.needs-validation');

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

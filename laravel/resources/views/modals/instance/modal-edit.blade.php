<div class="modal fade" id="modalEditInstance-{{$instanceModal->id}}" tabindex="-1" aria-labelledby="modalEditInstance-{{$instanceModal->id}}-Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="modalEditInstance-{{$instanceModal->id}}-Label">Edit Instance: {{$instanceModal->virtualserver_name}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('instance.update', ['instance_id' => $instance->id]) }}" class="row g-3 needs-validation" novalidate>
                @method('patch')
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="validationHost">Host</label>
                                <input type="text" class="form-control" id="validationHost" name="host" value="{{ old('host', $instanceModal->host) }}" placeholder="e.g. my.teamspeak.local or 192.168.2.87" required>
                                <div class="form-text">The hostname, domain or IP address of your Teamspeak server.</div>
                                <div class="valid-feedback">{{ __("Looks good!") }}</div>
                                <div class="invalid-feedback">{{ __("Please provide a valid domain or IP address.") }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="validationVoicePort">Voice Port</label>
                                <input type="number" class="form-control" id="validationVoicePort" name="voice_port" min="1" max="65535" value="{{ old('voice_port', $instanceModal->voice_port) }}" required>
                                <div class="form-text">The Voice port of the TeamSpeak server to connect at.</div>
                                <div class="valid-feedback">{{ __("Looks good!") }}</div>
                                <div class="invalid-feedback">{{ __("Please provide a valid port.") }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="validationServerQueryPort">ServerQuery Port</label>
                                <input type="number" class="form-control" id="validationServerQueryPort" name="serverquery_port" min="1" max="65535" value="{{ old('serverquery_port', $instanceModal->serverquery_port) }}" required>
                                <div class="form-text">The ServerQuery port of the Teamspeak server for executing commands and gathering data</div>
                                <div class="valid-feedback">{{ __("Looks good!") }}</div>
                                <div class="invalid-feedback">{{ __("Please provide a valid port.") }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="validationServerQueryUsername">ServerQuery Username</label>
                                <input type="text" class="form-control" id="validationServerQueryUsername" name="serverquery_username" value="{{ old('serverquery_username', $instanceModal->serverquery_username) }}" placeholder="e.g. serveradmin" required>
                                <div class="form-text">The ServerQuery username for the authentication.</div>
                                <div class="valid-feedback">{{ __("Looks good!") }}</div>
                                <div class="invalid-feedback">{{ __("Please provide a valid username.") }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="validationServerQueryPassword">ServerQuery Password</label>
                                <input type="password" class="form-control" id="validationServerQueryPassword" name="serverquery_password" required>
                                <div class="form-text">The password of the previous defined ServerQuery username</div>
                                <div class="valid-feedback">{{ __("Looks good!") }}</div>
                                <div class="invalid-feedback">{{ __("Please provide a valid password.") }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="validationClientNickname">Client Nickname</label>
                                <input type="text" class="form-control" id="validationClientNickname" maxlength="30" name="client_nickname" value="{{ old('client_nickname', $instanceModal->client_nickname) }}" required>
                                <div class="form-text">How this client should be named on your TeamSpeak server. (Maximum length: 30 characters)</div>
                                <div class="valid-feedback">{{ __("Looks good!") }}</div>
                                <div class="invalid-feedback">{{ __("Please provide a valid nickname.") }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="validationDefaultChannelId" class="form-label fw-bold">Default Channel</label>
                                <select class="form-select" name="default_channel_id" id="validationDefaultChannelId" aria-describedby="defaultChannelIdHelp">
                                    <option value="" selected>Default Channel</option>
                                    @foreach ($channel_list as $channelInstance)
                                        @foreach($channelInstance[$instanceModal->id] as $channel)
                                            @if (old('default_channel_id', $instanceModal->default_channel_id) == $channel->cid) "selected"
                                            <option value="{{ $channel->cid }}" selected>{{ $channel->name }}</option>
                                            @else
                                                <option value="{{ $channel->cid }}">{{ $channel->name }}</option>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </select>
                                <div id="defaultChannelIdHelp" class="form-text">The default channel to which the client should connect / switch on your TeamSpeak server.</div>
                                <div class="valid-feedback">{{ __("Looks good!") }}</div>
                                <div class="invalid-feedback">{{ __("Please provide a valid channel (ID).") }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="validationAutostart" class="form-check-label fw-bold">Enable autostart</label>
                                @if (old('autostart_enabled', $instance->autostart_enabled))
                                    <input class="form-check-input ms-2" id="validationAutostart" type="checkbox" name="autostart_enabled" aria-describedby="autostartHelp" checked>
                                @else
                                    <input class="form-check-input ms-2" id="validationAutostart" type="checkbox" name="autostart_enabled" aria-describedby="autostartHelp">
                                @endif
                                <div id="autostartHelp" class="form-text">When enabled, the application automatically starts the bot instance after up to 5 minutes, if it should not run yet.</div>
                                <div class="valid-feedback">{{ __("Looks good!") }}</div>
                                <div class="invalid-feedback">{{ __("You can only enable or disable this checkbox.") }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
            @include('inc.form-validation')
        </div>
    </div>
</div>

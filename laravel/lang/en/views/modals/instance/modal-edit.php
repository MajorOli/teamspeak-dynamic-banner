<?php

return [

    /**
     * Modal Headline
     */
    'edit_instance' => 'Edit Instance <code>:virtualserver_name</code>',

    /**
     * Form
     */
    'host' => 'Host',
    'host_placeholder' => 'e. g. my.teamspeak.local or 192.168.2.87',
    'host_help' => 'The hostname, domain or IP address of your TeamSpeak server.',

    'voice_port' => 'Voice Port',
    'voice_port_placeholder' => 'e. g. 9987',
    'voice_port_help' => 'The voice port of the TeamSpeak server to connect at.',

    'serverquery_port' => 'ServerQuery Port',
    'serverquery_port_placeholder' => 'e. g. 10022',
    'serverquery_port_help' => 'The serverquery port of the TeamSpeak server for executing commands and gathering data.',

    'serverquery_encryption' => 'Enable ServerQuery Encryption (SSH)',
    'serverquery_encryption_help' => 'When enabled, the ServerQuery connection will be established via an encrypted SSH connection. The respective ServerQuery port must be set.',

    'serverquery_username' => 'ServerQuery Username',
    'serverquery_username_placeholder' => 'e. g. serveradmin',
    'serverquery_username_help' => 'The serverquery username for the authentication.',

    'serverquery_password' => 'ServerQuery Password',
    'serverquery_password_placeholder' => 'e. g. secretPassword',
    'serverquery_password_help' => 'The password of the previous defined serverquery username.',

    'client_nickname' => 'Client Nickname',
    'client_nickname_placeholder' => 'e. g. Dynamic Banner',
    'client_nickname_help' => 'How this client should be named on your TeamSpeak server. (maximum 30 characters)',

    'default_channel' => 'Default Channel',
    'default_channel_help' => 'The default channel to which the client should connect / switch on your TeamSpeak server.',

    'autostart' => 'Enable autostart',
    'autostart_help' => 'When enabled, the application automatically starts the bot after up to 5 minutes, if it should not run yet.',

    /**
     * Buttons
     */
    'dismiss_button' => 'Cancel',
    'update_button' => 'Update',

    /**
     * Form Validation
     */
    'form_validation_looks_good' => 'Looks good!',
    'host_validation_error' => 'Please provide a valid hostname, domain or IP address!',
    'voice_port_validation_error' => 'Please provide a valid voice port!',
    'serverquery_port_validation_error' => 'Please provide a valid serverquery port!',
    'serverquery_encryption_validation_error' => 'You can only enable or disable this checkbox!',
    'serverquery_username_validation_error' => 'Please provide a valid serverquery username!',
    'serverquery_password_validation_error' => 'Please provide a valid serverquery password!',
    'client_nickname_validation_error' => 'Please provide a valid client nickname!',
    'default_channel_validation_error' => 'Please select an available default channel!',
    'autostart_validation_error' => 'You can only enable or disable this checkbox!',

];

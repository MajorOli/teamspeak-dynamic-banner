<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Models\Instance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use PlanetTeamSpeak\TeamSpeak3Framework\Exception\ServerQueryException;
use PlanetTeamSpeak\TeamSpeak3Framework\Node\Server;
use Predis\Connection\ConnectionException;

class BannerVariableController extends Controller
{
    /**
     * Class properties
     */
    private ?Server $virtualserver = null;

    /**
     * The class constructor
     */
    public function __construct(?Server $virtualserver)
    {
        $this->virtualserver = $virtualserver;
    }

    /**
     * The class destructor
     */
    public function __destruct()
    {
        unset($this->virtualserver);
    }

    /**
     * Returns various datetime formatted values.
     */
    public function get_current_time_data(): array
    {
        $current_datetime = Carbon::now();

        $datetimes = [
            'current_time_utc_hi' => $current_datetime->setTimezone('UTC')->format('H:i'),
            'current_time_europe_berlin_hi' => $current_datetime->setTimezone('Europe/Berlin')->format('H:i'),
            'current_date_utc_ymd' => $current_datetime->setTimezone('UTC')->format('Y-m-d'),
            'current_date_europe_berlin_dmy' => $current_datetime->setTimezone('Europe/Berlin')->format('d.m.Y'),
            'current_datetime_utc_ymd_hi' => $current_datetime->setTimezone('UTC')->format('Y-m-d H:i'),
            'current_datetime_europe_berlin_dmy_hi' => $current_datetime->setTimezone('Europe/Berlin')->format('d.m.Y H:i'),
        ];

        return array_change_key_case($datetimes, CASE_UPPER);
    }

    /**
     * Returns a client list with various information.
     */
    public function get_current_client_list(): array
    {
        $this->virtualserver->clientListReset();

        $clientlist = [];
        foreach ($this->virtualserver->clientList(['client_type' => 0]) as $client) {
            $clientlist['client_'.$client->client_database_id.'_database_id'] = $client->client_database_id;
            $clientlist['client_'.$client->client_database_id.'_id'] = $client->clid;
            $clientlist['client_'.$client->client_database_id.'_nickname'] = $client->client_nickname;
            $clientlist['client_'.$client->client_database_id.'_servergroups'] = $client->client_servergroups;
            $clientlist['client_'.$client->client_database_id.'_version'] = $client->client_version;
            $clientlist['client_'.$client->client_database_id.'_platform'] = $client->client_platform;
            $clientlist['client_'.$client->client_database_id.'_country'] = $client->client_country;
            $clientlist['client_'.$client->client_database_id.'_connection_client_ip'] = $client->connection_client_ip;
        }

        return array_change_key_case($clientlist, CASE_UPPER);
    }

    /**
     * Returns a servergroup list with various information.
     */
    public function get_current_servergroup_list(): array
    {
        $this->virtualserver->clientListReset();
        $this->virtualserver->serverGroupListReset();

        /**
         * SERVERGROUP MEMBER ONLINE COUNTER VARIABLE
         */
        $client_servergroup_ids = [];
        foreach ($this->virtualserver->clientList(['client_type' => 0]) as $client) {
            $client_servergroup_ids = array_merge($client_servergroup_ids, explode(',', $client->client_servergroups));
        }

        /**
         * VIRTUALSERVER SERVERGROUPS
         */
        $servergroups = [];
        foreach ($this->virtualserver->serverGroupList(['type' => 1]) as $servergroup) {
            $servergroups['servergroup_'.$servergroup->sgid.'_id'] = $servergroup->sgid;
            $servergroups['servergroup_'.$servergroup->sgid.'_name'] = $servergroup->name;
            try {
                $servergroups['servergroup_'.$servergroup->sgid.'_member_total_count'] = count($this->virtualserver->serverGroupClientList($servergroup->sgid));
            } catch (ServerQueryException $serverquery_exception) {
                // TODO: Remove this try-catch and rather fix it properly. We are currently fully ignoring some servergroups, which can't be parsed properly.
                // https://github.com/Sebbo94BY/teamspeak-dynamic-banner/issues/12
                if ($serverquery_exception->getCode() == 1538) {
                    // Error: invalid parameter
                    // Until this issue is fixed in the TS3PHPFramework: Ignore this error and do not check this servergroup.
                    $servergroups['servergroup_'.$servergroup->sgid.'_member_total_count'] = 0;
                    continue;
                }
            }
            $servergroups['servergroup_'.$servergroup->sgid.'_member_online_count'] = (in_array($servergroup->sgid, $client_servergroup_ids)) ? array_count_values($client_servergroup_ids)[$servergroup->sgid] : 0;
        }

        return array_change_key_case($servergroups, CASE_UPPER);
    }

    /**
     * Returns various virtualserver information.
     */
    public function get_current_virtualserver_info(): array
    {
        $virtualserver_info = [];

        /**
         * VIRTUALSERVER NODE INFO
         */
        $virtualserver_info = array_merge($virtualserver_info, $this->virtualserver->getInfo(true, true));

        /**
         * VIRTUALSERVER CONNECTION INFO
         */
        $virtualserver_info = array_merge($virtualserver_info, $this->virtualserver->connectionInfo());

        return array_change_key_case($virtualserver_info, CASE_UPPER);
    }

    /**
     * Get client specific information.
     */
    public function get_client_specific_info_from_cache(Instance $instance, string $ip_address): array
    {
        $client_info = [];

        try {
            $client_variables = Redis::hgetall('instance_'.$instance->id.'_clientlist');
        } catch (ConnectionException) {
            return $client_info;
        }

        $client_by_ip_address_array_key = array_search($ip_address, $client_variables);

        if ($client_by_ip_address_array_key === false) {
            preg_match('/[0-9]+/', array_key_first($client_variables), $client_database_id);
        } else {
            preg_match('/[0-9]+/', $client_by_ip_address_array_key, $client_database_id);
        }

        if (empty($client_database_id)) {
            return $client_info;
        }
        $client_database_id = intval($client_database_id[0]);

        $client_variable_array_keys = preg_grep("/\_$client_database_id\_/", array_keys($client_variables));

        if (empty($client_variable_array_keys)) {
            return $client_info;
        }

        foreach ($client_variable_array_keys as $client_variable_key) {
            $client_info[str_replace("_$client_database_id", '', $client_variable_key)] = $client_variables[$client_variable_key];
        }

        return array_change_key_case($client_info, CASE_UPPER);
    }
}

<?php

namespace Webaccess\BiometLaravel\Services;

use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Client;

class ClientManager
{
    public static function getAll($paginate = false)
    {
        $clients = Client::orderBy('created_at');

        return ($paginate) ? $clients->paginate($paginate) : $clients->get();
    }

    public static function getByID($clientID)
    {
        return Client::find($clientID);
    }

    /**
     * @param $name
     * @param $accessLimitDate
     * @return Client
     */
    public static function createClient($name, $accessLimitDate = null)
    {
        $client = new Client();
        $client->id = Uuid::uuid4()->toString();
        $client->name = $name;
        $client->access_limit_date = $accessLimitDate;

        $client->save();

        return $client->id;
    }

    /**
     * @param $clientID
     * @param $name
     * @param $accessLimitDate
     * @return bool
     */
    public static function udpateClient($clientID, $name, $accessLimitDate)
    {
        if ($client = Client::find($clientID)) {
            $client->name = $name;
            $client->access_limit_date = $accessLimitDate;
            $client->save();

            return true;
        }

        return false;
    }

    /**
     * @param $clientID
     * @return bool
     */
    public static function deleteClient($clientID)
    {
        if ($client = Client::find($clientID)) {
            $client->delete();

            return true;
        }

        return false;
    }
}

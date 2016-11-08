<?php

namespace Webaccess\BiometLaravel\Services;

use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Client;

class ClientManager
{

    public static function getAll()
    {
        return Client::orderBy('created_at')->paginate(10);
    }

    public static function getClient($clientID)
    {
        return Client::find($clientID);
    }

    /**
     * @param $name
     * @return Client
     */
    public static function createClient($name)
    {
        $client = new Client();
        $client->id = Uuid::uuid4()->toString();
        $client->name = $name;

        return $client->save();
    }

    /**
     * @param $clientID
     * @param $name
     * @return bool
     */
    public static function udpateClient($clientID, $name)
    {
        if ($client = Client::find($clientID)) {
            $client->name = $name;
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
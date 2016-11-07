<?php

namespace Webaccess\BiometLaravel\Services;

use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Client;

class ClientManager
{

    public static function getAll()
    {
        return Client::orderBy('created_at')->paginate(10);
    }

    public static function getClient($userID)
    {
        return Client::find($userID);
    }

    /**
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @param bool $isAdministrator
     * @return Client
     */
    public static function createClient($firstName, $lastName, $email, $password, $isAdministrator = false)
    {
        $user = new Client();
        $user->id = Uuid::uuid4()->toString();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->is_administrator = $isAdministrator;

        return $user->save();
    }

    /**
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @param bool $isAdministrator
     * @return bool
     */
    public static function udpateClient($userID, $firstName, $lastName, $email, $password, $isAdministrator)
    {
        if ($user = Client::find($userID)) {
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->is_administrator = $isAdministrator;
            $user->save();

            return true;
        }

        return false;
    }

    /**
     * @param $userID
     * @return bool
     */
    public static function deleteClient($userID)
    {
        if ($user = Client::find($userID)) {
            $user->delete();

            return true;
        }

        return false;
    }
}
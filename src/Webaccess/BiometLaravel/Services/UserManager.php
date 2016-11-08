<?php

namespace Webaccess\BiometLaravel\Services;

use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\User;

class UserManager
{

    public static function getAll()
    {
        return User::with('client')->orderBy('created_at')->paginate(10);
    }

    public static function getUser($userID)
    {
        return User::find($userID);
    }

    /**
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @param $clientID
     * @param bool $isAdministrator
     * @return User
     */
    public static function createUser($firstName, $lastName, $email, $password, $clientID, $isAdministrator = false)
    {
        $user = new User();
        $user->id = Uuid::uuid4()->toString();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->client_id = $clientID;
        $user->is_administrator = $isAdministrator;

        return $user->save();
    }

    /**
     * @param $userID
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @param $clientID
     * @param bool $isAdministrator
     * @return bool
     */
    public static function udpateUser($userID, $firstName, $lastName, $email, $password, $clientID, $isAdministrator)
    {
        if ($user = User::find($userID)) {
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->client_id = $clientID;
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
    public static function deleteUser($userID)
    {
        if ($user = User::find($userID)) {
            $user->delete();

            return true;
        }

        return false;
    }
}
<?php

namespace Webaccess\BiometLaravel\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
     * @param int $profileID
     * @return User
     */
    public static function createUser($firstName, $lastName, $email, $password, $clientID = null, $profileID = User::PROFILE_ID_CLIENT)
    {
        $user = new User();
        $user->id = Uuid::uuid4()->toString();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->client_id = $clientID;
        $user->profile_id = $profileID;

        $user->save();

        Log::info('Created user successfully: ' . json_encode($user) . $password . "\n");

        return $user->id;
    }

    /**
     * @param $userID
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @param $clientID
     * @param int $profileID
     * @return bool
     */
    public static function udpateUser($userID, $firstName, $lastName, $email, $password, $clientID, $profileID)
    {
        if ($user = User::find($userID)) {
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->email = $email;
            if ($password != '') $user->password = Hash::make($password);
            $user->client_id = $clientID;
            $user->profile_id = $profileID;
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
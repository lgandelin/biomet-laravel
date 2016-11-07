<?php

namespace Webaccess\BiometLaravel\Services;

use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\User;

class UserManager
{
    /**
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $password
     * @param bool $isAdministrator
     * @return User
     */
    public static function createUser($firstName, $lastName, $email, $password, $isAdministrator = false)
    {
        $user = new User();
        $user->id = Uuid::uuid4()->toString();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->is_administrator = $isAdministrator;

        return $user->save();
    }

    public static function getAll()
    {
        return [];
    }

    public static function getUser($userID)
    {
        return User::find($userID);
    }
}
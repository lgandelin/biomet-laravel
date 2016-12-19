<?php

namespace Webaccess\BiometLaravel;

use DateTime;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Webaccess\BiometLaravel\Models\User;
use Webaccess\BiometLaravel\Services\ClientManager;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('can-view-facility-tab', function ($user, $facility, $tab) {

            //Facility not found
            if (!$facility)
                return false;

            //Facility not belonging to any client
            elseif (!$facility->client_id)
                return false;

            //Tab disabled
            if (!in_array($tab, $facility->tabs))
                return false;

            //Profile check
            switch ($user->profile_id) {
                case User::PROFILE_ID_AROL_ENERGY_ADMINISTRATOR:
                    return true;
                break;

                case User::PROFILE_ID_CLIENT_ADMINISTRATOR:
                case User::PROFILE_ID_CLIENT_USER:

                    //User not belonging to the facility client
                    if ($user->client_id && $user->client_id !== $facility->client_id)
                        return false;
                    else {
                        $client = ClientManager::getByID($facility->client_id);

                        //Client access limit date reached
                        if (!$client || ($client->access_limit_date && DateTime::createFromFormat('Y-m-d', $client->access_limit_date) < new DateTime())) {
                            return false;
                        }
                    }

                    return true;
                break;

                case User::PROFILE_ID_PROVIDER:
                     if ($tab !== 10) {
                         return false;
                     }

                    return true;
                break;

                default:
                    return false;
                break;
            }
        });
    }
}

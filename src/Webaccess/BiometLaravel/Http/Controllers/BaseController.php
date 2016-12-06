<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webaccess\BiometLaravel\Models\User;
use Webaccess\BiometLaravel\Services\FacilityManager;

class BaseController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        view()->share('left_column_facilities', $this->getFacilities());
        view()->share('current_route', $request->route()->getName());
        view()->share('current_tab', isset($request->tab) ? $request->tab : 0);
    }

    protected function getUser()
    {
        return auth()->check() ? auth()->user() : false;
    }

    protected function getFacilities()
    {
        if (!$user = $this->getUser())
            return [];

        if ($user->profile_id == User::PROFILE_ID_ADMINISTRATOR)
            return FacilityManager::getAll(false);

        if ($user->profile_id == User::PROFILE_ID_CLIENT || User::PROFILE_ID_PROVIDER && $user->client_id)
            return FacilityManager::getByClient($user->client_id);

        return [];
    }
}
<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webaccess\BiometLaravel\Services\FacilityManager;

class BaseController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        view()->share('facilities', ($this->getUser()) ? FacilityManager::getByClient($this->getUser()->client_id) : []);
        view()->share('current_route', $request->route()->getName());
    }

    public function getUser()
    {
        return auth()->check() ? auth()->user() : false;
    }
}
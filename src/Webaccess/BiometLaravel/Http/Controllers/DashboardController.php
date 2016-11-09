<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Webaccess\BiometLaravel\Services\FacilityManager;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('biomet::pages.dashboard', [
            'facilities' => ($this->getUser()) ? FacilityManager::getByClient($this->getUser()->client_id) : []
        ]);
    }
}
<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Webaccess\BiometLaravel\Services\FacilityManager;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('biomet::pages.dashboard', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
            'facilities' => ($this->getUser()) ? FacilityManager::getByClient($this->getUser()->client_id) : []
        ]);
    }
}
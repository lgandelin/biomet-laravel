<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('biomet::pages.dashboard', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
            'facilities' => $this->getFacilities()
        ]);
    }
}
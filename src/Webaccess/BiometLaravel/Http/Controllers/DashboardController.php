<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webaccess\BiometLaravel\Services\FacilityManager;

class DashboardController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        return view('biomet::pages.dashboard', [
            'user' => $user,
            'facilities' => FacilityManager::getByClient($user->client_id)
        ]);
    }
}
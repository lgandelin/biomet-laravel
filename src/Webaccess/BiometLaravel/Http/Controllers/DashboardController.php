<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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

        return view('biomet::pages.index', [
            'user' => $user,
        ]);
    }
}
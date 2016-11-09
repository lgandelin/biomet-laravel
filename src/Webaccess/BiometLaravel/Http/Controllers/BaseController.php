<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getUser()
    {
        return auth()->check() ? auth()->user() : false;
    }
}
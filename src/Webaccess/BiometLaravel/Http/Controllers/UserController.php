<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webaccess\BiometLaravel\Services\UserManager;

class UserController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('biomet::pages.users.index', [
            'users' => UserManager::getAll(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        return view('biomet::pages.users.add', [
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function store(Request $request)
    {
        try {
            if ($request->input('password') != '' && $request->input('password') != $request->input('password_confirmation')) {
                $request->session()->flash('error', trans('biomet::users.update_password_confirmation_error'));

                return redirect()->route('users_add');
            } else {
                UserManager::createUser(
                    $request->input('first_name'),
                    $request->input('last_name'),
                    $request->input('email'),
                    $request->input('password'),
                    ($request->input('is_administrator') == 'y') ? true : false
                );
                $request->session()->flash('confirmation', trans('biomet::users.add_user_success'));

                return redirect()->route('users');
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('biomet::users.add_user_error'));

            return redirect()->route('users_add');
        }
    }

    public function edit(Request $request)
    {
        try {
            $user = UserManager::getUser($request->id);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('biomet::users.user_not_found_error'));

            return redirect()->route('users');
        }

        return view('biomet::pages.users.edit', [
            'user' => $user,
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update(Request $request)
    {
        try {
            if ($request->input('password') != '' && $request->input('password') != $request->input('password_confirmation')) {
                $request->session()->flash('error', trans('biomet::users.update_password_confirmation_error'));

                return redirect()->route('users_edit', ['id' => $request->input('user_id')]);
            } else {
                UserManager::udpateUser(
                    $request->input('user_id'),
                    $request->input('first_name'),
                    $request->input('last_name'),
                    $request->input('email'),
                    $request->input('password'),
                    ($request->input('is_administrator') == 'y') ? true : false
                );
                $request->session()->flash('confirmation', trans('biomet::users.edit_user_success'));
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('users_edit', ['id' => $request->input('user_id')]);
    }

    public function delete(Request $request)
    {
        try {
            UserManager::deleteUser($request->id);
            $request->session()->flash('confirmation', trans('biomet::users.delete_user_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('biomet::users.delete_user_error'));
        }

        return redirect()->route('users');
    }
}
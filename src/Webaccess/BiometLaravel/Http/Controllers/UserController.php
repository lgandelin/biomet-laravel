<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\BiometLaravel\Services\UserManager;

class UserController
{
    public function index()
    {
        return view('biomet::pages.index', [
            'users' => UserManager::getAll(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function add()
    {
        return view('biomet::pages.add');
    }

    public function store()
    {
        try {
            if (Input::get('password') != '' && Input::get('password') != Input::get('password_confirmation')) {

            } else {
                UserManager::createUser(
                    Input::get('first_name'),
                    Input::get('last_name'),
                    Input::get('email'),
                    Input::get('password'),
                    Input::get('is_administrator')

                );
                $this->request->session()->flash('confirmation', trans('biomet::users.add_user_success'));
            }
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('users_index');
    }

    public function edit($userID)
    {
        try {
            $user = UserManager::getUser($userID);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::users.user_not_found'));

            return redirect()->route('users_index');
        }

        return view('biomet::pages.edit', [
            'user' => $user,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            UserManager::udpateUser(
                Input::get('user_id'),
                Input::get('first_name'),
                Input::get('last_name'),
                Input::get('email'),
                Input::get('password'),
                (Input::get('is_administrator') == 'y') ? true : false
            );
            $this->request->session()->flash('confirmation', trans('biomet::users.edit_user_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('users_edit', ['id' => Input::get('user_id')]);
    }

    public function generate_password($userID)
    {
        app()->make('UserManager')->generateNewPassword($userID);
        $this->request->session()->flash('confirmation', trans('biomet::users.password_generated_success'));

        return redirect()->route('users_edit', ['id' => Input::get('user_id')]);
    }

    public function delete($userID)
    {
        try {
            app()->make('UserManager')->deleteUser($userID);
            $this->request->session()->flash('confirmation', trans('biomet::users.delete_user_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::users.delete_user_error'));
        }

        return redirect()->route('users_index');
    }
}
<?php

namespace Webaccess\BiometLaravel\Http\Controllers\Admin;

use Webaccess\BiometLaravel\Http\Controllers\BaseController;
use Webaccess\BiometLaravel\Services\ClientManager;
use Webaccess\BiometLaravel\Services\UserManager;

class UserController extends BaseController
{
    public function index()
    {
        parent::__construct($this->request);
        $itemsPerPage = isset($this->request->items_per_page) ? $this->request->items_per_page : 10;
        
        return view('biomet::pages.users.index', [
            'users' => UserManager::getAll($itemsPerPage, $this->request->filter_client_id, $this->request->filter_client_name, $this->request->filter_profile_id),
            'clients' => ClientManager::getAll(false),
            'filter_client_id' => $this->request->filter_client_id,
            'filter_client_name' => $this->request->filter_client_name,
            'filter_profile_id' => $this->request->filter_profile_id,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
            'items_per_page' => $this->request->items_per_page,
        ]);
    }

    public function add()
    {
        parent::__construct($this->request);

        return view('biomet::pages.users.add', [
            'clients' => ClientManager::getAll(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function store()
    {
        try {
            if ($this->request->input('password') != '' && $this->request->input('password') != $this->request->input('password_confirmation')) {
                $this->request->session()->flash('error', trans('biomet::users.update_password_confirmation_error'));

                return redirect()->route('users_add');
            } else {
                UserManager::createUser(
                    $this->request->input('first_name'),
                    $this->request->input('last_name'),
                    $this->request->input('email'),
                    $this->request->input('password'),
                    $this->request->input('client_id'),
                    $this->request->input('profile_id')
                );
                $this->request->session()->flash('confirmation', trans('biomet::users.add_user_success'));

                return redirect()->route('users');
            }
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::users.add_user_error'));

            return redirect()->route('users_add');
        }
    }

    public function edit()
    {
        parent::__construct($this->request);

        try {
            $user = UserManager::getUser($this->request->id);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::users.user_not_found_error'));

            return redirect()->route('users');
        }

        return view('biomet::pages.users.edit', [
            'user' => $user,
            'clients' => ClientManager::getAll(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            if ($this->request->input('password') != '' && $this->request->input('password') != $this->request->input('password_confirmation')) {
                $this->request->session()->flash('error', trans('biomet::users.update_password_confirmation_error'));

                return redirect()->route('users_edit', ['id' => $this->request->input('user_id')]);
            } else {
                UserManager::udpateUser(
                    $this->request->input('user_id'),
                    $this->request->input('first_name'),
                    $this->request->input('last_name'),
                    $this->request->input('email'),
                    $this->request->input('password'),
                    $this->request->input('client_id'),
                    $this->request->input('profile_id')
                );
                $this->request->session()->flash('confirmation', trans('biomet::users.edit_user_success'));
            }
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('users_edit', ['id' => $this->request->input('user_id')]);
    }

    public function delete()
    {
        try {
            UserManager::deleteUser($this->request->id);
            $this->request->session()->flash('confirmation', trans('biomet::users.delete_user_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::users.delete_user_error'));
        }

        return redirect()->route('users');
    }
}

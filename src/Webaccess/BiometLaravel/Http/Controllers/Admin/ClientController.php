<?php

namespace Webaccess\BiometLaravel\Http\Controllers\Admin;

use Webaccess\BiometLaravel\Http\Controllers\BaseController;
use Webaccess\BiometLaravel\Services\ClientManager;

class ClientController extends BaseController
{
    public function index()
    {
        parent::__construct($this->request);
        $itemsPerPage = isset($this->request->items_per_page) ? $this->request->items_per_page : 10;

        return view('biomet::pages.clients.index', [
            'clients' => ClientManager::getAll($itemsPerPage),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
            'items_per_page' => $this->request->items_per_page,
        ]);
    }

    public function add()
    {
        parent::__construct($this->request);

        return view('biomet::pages.clients.add', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function store()
    {
        try {
            ClientManager::createClient(
                $this->request->input('name'),
                $this->request->input('access_limit_date'),
                $this->request->input('users_limit')
            );
            $this->request->session()->flash('confirmation', trans('biomet::clients.add_client_success'));

            return redirect()->route('clients');
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::clients.add_client_error'));

            return redirect()->route('clients_add');
        }
    }

    public function edit()
    {
        parent::__construct($this->request);

        try {
            $client = ClientManager::getByID($this->request->id);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::clients.client_not_found_error'));

            return redirect()->route('clients');
        }

        return view('biomet::pages.clients.edit', [
            'client' => $client,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            ClientManager::udpateClient(
                $this->request->input('client_id'),
                $this->request->input('name'),
                $this->request->input('access_limit_date'),
                $this->request->input('users_limit')
            );
            $this->request->session()->flash('confirmation', trans('biomet::clients.edit_client_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::clients.update_client_error'));
        }

        return redirect()->route('clients_edit', ['id' => $this->request->input('client_id')]);
    }

    public function delete()
    {
        try {
            ClientManager::deleteClient($this->request->id);
            $this->request->session()->flash('confirmation', trans('biomet::clients.delete_client_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::clients.delete_client_error'));
        }

        return redirect()->route('clients');
    }
}

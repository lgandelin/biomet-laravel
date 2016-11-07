<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Webaccess\BiometLaravel\Services\ClientManager;

class ClientController
{
    public function index(Request $request)
    {
        return view('biomet::pages.clients.index', [
            'clients' => ClientManager::getAll(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        return view('biomet::pages.clients.add', [
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function store(Request $request)
    {
        try {
            ClientManager::createClient(
                $request->input('name')
            );
            $request->session()->flash('confirmation', trans('biomet::clients.add_client_success'));

            return redirect()->route('clients');
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('biomet::clients.add_client_error'));

            return redirect()->route('clients_add');
        }
    }

    public function edit(Request $request)
    {
        try {
            $client = ClientManager::getClient($request->id);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('biomet::clients.client_not_found_error'));

            return redirect()->route('clients');
        }

        return view('biomet::pages.clients.edit', [
            'client' => $client,
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update(Request $request)
    {
        try {
            ClientManager::udpateClient(
                $request->input('client_id'),
                $request->input('name')
            );
            $request->session()->flash('confirmation', trans('biomet::clients.edit_client_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('clients_edit', ['id' => $request->input('client_id')]);
    }

    public function delete(Request $request)
    {
        try {
            ClientManager::deleteClient($request->id);
            $request->session()->flash('confirmation', trans('biomet::clients.delete_client_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('biomet::clients.delete_client_error'));
        }

        return redirect()->route('clients');
    }
}
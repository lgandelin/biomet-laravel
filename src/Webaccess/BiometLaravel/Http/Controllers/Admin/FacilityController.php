<?php

namespace Webaccess\BiometLaravel\Http\Controllers\Admin;

use DateTime;
use Webaccess\BiometLaravel\Http\Controllers\BaseController;
use Webaccess\BiometLaravel\Services\ClientManager;
use Webaccess\BiometLaravel\Services\FacilityManager;

class FacilityController extends BaseController
{
    public function index()
    {
        parent::__construct($this->request);
        $itemsPerPage = isset($this->request->items_per_page) ? $this->request->items_per_page : 10;

        return view('biomet::pages.facilities.index', [
            'facilities' => FacilityManager::getAll($itemsPerPage, $this->request->filter_client_id, $this->request->filter_client_name, $this->request->order_by, $this->request->order),
            'clients' => ClientManager::getAll(false),
            'filter_client_id' => $this->request->filter_client_id,
            'filter_client_name' => $this->request->filter_client_name,
            'order_by' => $this->request->order_by,
            'order' => $this->request->order,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
            'items_per_page' => $this->request->items_per_page,
        ]);
    }

    public function add()
    {
        parent::__construct($this->request);

        return view('biomet::pages.facilities.add', [
            'clients' => ClientManager::getAll(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function store()
    {
        try {
            FacilityManager::createFacility(
                $this->request->input('name'),
                $this->request->input('longitude'),
                $this->request->input('latitude'),
                $this->request->input('address'),
                $this->request->input('city'),
                $this->request->input('department'),
                $this->request->input('country'),
                $this->request->input('client_id'),
                $this->request->input('technology'),
                $this->request->input('serial_number'),
                $this->request->input('startup_date') ? DateTime::createFromFormat('d/m/Y', $this->request->input('startup_date')) : null,
                $this->request->input('tabs') ? implode(',', $this->request->input('tabs')) : null
            );
            $this->request->session()->flash('confirmation', trans('biomet::facilities.add_facility_success'));

            return redirect()->route('facilities');
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::facilities.add_facility_error'));

            return redirect()->route('facilities_add');
        }
    }

    public function edit()
    {
        parent::__construct($this->request);

        try {
            $facility = FacilityManager::getByID($this->request->id);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::facilities.facility_not_found_error'));

            return redirect()->route('facilities');
        }

        return view('biomet::pages.facilities.edit', [
            'facility' => $facility,
            'clients' => ClientManager::getAll(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            FacilityManager::udpateFacility(
                $this->request->input('facility_id'),
                $this->request->input('name'),
                $this->request->input('longitude'),
                $this->request->input('latitude'),
                $this->request->input('address'),
                $this->request->input('city'),
                $this->request->input('department'),
                $this->request->input('country'),
                $this->request->input('client_id'),
                $this->request->input('technology'),
                $this->request->input('serial_number'),
                $this->request->input('startup_date') ? DateTime::createFromFormat('d/m/Y', $this->request->input('startup_date')) : null,
                $this->request->input('tabs') ? implode(',', $this->request->input('tabs')) : null
            );
            $this->request->session()->flash('confirmation', trans('biomet::facilities.edit_facility_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::facilities.update_facility_error'));
        }

        return redirect()->route('facilities_edit', ['id' => $this->request->input('facility_id')]);
    }

    public function delete()
    {
        try {
            FacilityManager::deleteFacility($this->request->id);
            $this->request->session()->flash('confirmation', trans('biomet::facilities.delete_facility_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::facilities.delete_facility_error'));
        }

        return redirect()->route('facilities');
    }
}

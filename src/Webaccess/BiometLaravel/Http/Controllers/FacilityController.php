<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Webaccess\BiometLaravel\Services\ClientManager;
use Webaccess\BiometLaravel\Services\FacilityManager;

class FacilityController extends BaseController
{
    public function index()
    {
        return view('biomet::pages.facilities.index', [
            'facilities' => FacilityManager::getAll(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function view()
    {
        if (!$this->canViewFacility($this->request->id)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facilities');
        }

        return view('biomet::pages.facility.index', [
            'facility' => FacilityManager::getByID($this->request->id),
        ]);
    }

    public function add()
    {
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
                $this->request->input('client_id')
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
                $this->request->input('client_id')
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

    /**
     * @param $facilityID
     * @return bool
     */
    private function canViewFacility($facilityID)
    {
        $user = auth()->user();
        if ($facility = FacilityManager::getByID($facilityID)) {
            return $user->client_id === $facility->client_id;
        }

        return false;
    }
}
<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use DateTime;
use Webaccess\BiometLaravel\Services\ClientManager;
use Webaccess\BiometLaravel\Services\FacilityManager;

class FacilityController extends BaseController
{
    public function tab1()
    {
        parent::__construct($this->request);

        if (!$this->canViewFacility($this->request->id)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('dashboard');
        }

        return view('biomet::pages.facility.tabs.1', [
            'current_facility' => FacilityManager::getByID($this->request->id),
        ]);
    }

    /**
     * @param $facilityID
     * @return bool
     */
    private function canViewFacility($facilityID)
    {
        parent::__construct($this->request);
        $user = auth()->user();
        $facility = FacilityManager::getByID($facilityID);

        if (!$facility || $user->client_id !== $facility->client_id) {
            return false;
        }

        $client = ClientManager::getByID($facility->client_id);
        if (!$client || ($client->access_limit_date && DateTime::createFromFormat('Y-m-d', $client->access_limit_date) < new DateTime())) {
            return false;
        }

        return true;
    }
}
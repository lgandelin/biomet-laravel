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

    public function graph()
    {
        //arguments : start_date, end_date, key
        return view('biomet::pages.facility.includes.graph', [
            'series' => json_encode(FacilityManager::getData()),
        ])->render();
    }

    /**
     * @param $facilityID
     * @return bool
     */
    private function canViewFacility($facilityID)
    {
        parent::__construct($this->request);

        $user = $this->getUser();
        $facility = FacilityManager::getByID($facilityID);

        if (!$facility)
            return false;

        if ($facility && $facility->client_id && $user->client_id && $user->client_id !== $facility->client_id)
            return false;

        if ($facility->client_id) {
            $client = ClientManager::getByID($facility->client_id);

            if (!$client || ($client->access_limit_date && DateTime::createFromFormat('Y-m-d', $client->access_limit_date) < new DateTime())) {
                return false;
            }
        }

        return true;
    }
}
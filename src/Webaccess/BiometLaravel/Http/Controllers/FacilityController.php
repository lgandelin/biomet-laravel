<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Webaccess\BiometLaravel\Services\FacilityManager;

class FacilityController extends BaseController
{
    public function index()
    {
        if (!$this->canViewFacility($this->request->id)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('dashboard');
        }

        return view('biomet::pages.facility.index', [
            'facility' => FacilityManager::getByID($this->request->id),
        ]);
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
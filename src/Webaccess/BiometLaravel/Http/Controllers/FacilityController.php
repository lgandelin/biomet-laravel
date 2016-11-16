<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use DateTime;
use Illuminate\Support\Facades\Gate;
use Webaccess\BiometLaravel\Services\FacilityManager;

class FacilityController extends BaseController
{
    public function index()
    {
        parent::__construct($this->request);

        return view('biomet::pages.facility.index', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'current_facility' => FacilityManager::getByID($this->request->id),
        ]);
    }

    public function tab1()
    {
        parent::__construct($this->request);

        if (!$this->canViewFacilityTab($this->request->id, 1)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        return view('biomet::pages.facility.tabs.1', [
            'current_facility' => FacilityManager::getByID($this->request->id),
        ]);
    }

    public function tab3()
    {
        parent::__construct($this->request);

        if (!$this->canViewFacilityTab($this->request->id, 3)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        return view('biomet::pages.facility.tabs.3', [
            'current_facility' => FacilityManager::getByID($this->request->id),
        ]);
    }

    public function tab4()
    {
        parent::__construct($this->request);

        if (!$this->canViewFacilityTab($this->request->id, 4)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        return view('biomet::pages.facility.tabs.4', [
            'current_facility' => FacilityManager::getByID($this->request->id),
        ]);
    }

    public function tab5()
    {
        parent::__construct($this->request);

        if (!$this->canViewFacilityTab($this->request->id, 5)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        return view('biomet::pages.facility.tabs.5', [
            'current_facility' => FacilityManager::getByID($this->request->id),
        ]);
    }

    public function tab7()
    {
        parent::__construct($this->request);

        if (!$this->canViewFacilityTab($this->request->id, 7)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        return view('biomet::pages.facility.tabs.7', [
            'current_facility' => FacilityManager::getByID($this->request->id),
        ]);
    }

    public function tab10()
    {
        parent::__construct($this->request);

        if (!$this->canViewFacilityTab($this->request->id, 10)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        return view('biomet::pages.facility.tabs.10', [
            'current_facility' => FacilityManager::getByID($this->request->id),
        ]);
    }

    public function graph()
    {
        return view('biomet::pages.facility.includes.graph', [
            'container_id' => $this->request->container_id,
            'title' => $this->request->title,
            'series' => json_encode(FacilityManager::getData(new DateTime($this->request->start_date), new DateTime($this->request->end_date), $this->request->facility_id, $this->request->keys)),
        ])->render();
    }

    /**
     * @param $facilityID
     * @param $tabNumber
     * @return bool
     */
    private function canViewFacilityTab($facilityID, $tabNumber)
    {
        return Gate::allows('can-view-facility-tab', [FacilityManager::getByID($facilityID), $tabNumber]);
    }
}
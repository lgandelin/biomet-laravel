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

        if (!Gate::allows('can-view-facility-tab', [FacilityManager::getByID($this->request->id), 1])) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));
            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        return view('biomet::pages.facility.tabs.1', [
            'current_facility' => FacilityManager::getByID($this->request->id),
        ]);
    }

    public function tab10()
    {
        parent::__construct($this->request);

        if (!Gate::allows('can-view-facility-tab', [FacilityManager::getByID($this->request->id), 10])) {
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
            'series' => json_encode(FacilityManager::getData(new DateTime($this->request->start_date), new DateTime($this->request->end_date), $this->request->facility_id, $this->request->keys)),
        ])->render();
    }
}
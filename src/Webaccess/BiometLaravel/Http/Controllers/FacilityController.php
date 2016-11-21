<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use DateTime;
use DirectoryIterator;
use Illuminate\Support\Facades\Gate;
use IteratorIterator;
use Webaccess\BiometLaravel\Services\AlarmManager;
use Webaccess\BiometLaravel\Services\FacilityManager;
use Webaccess\BiometLaravel\Services\InterventionManager;

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

    public function tab()
    {
        parent::__construct($this->request);

        $tab = isset($this->request->tab) ? $this->request->tab : 1;
        if (!$this->canViewFacilityTab($this->request->id, $tab)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        $data = [];
        $yesterdayDate = date('Y-m-d', strtotime( '-1 days' ));

        switch ($tab) {
            //Fetch alarms log
            case 9:
                $data['alarms'] = AlarmManager::getAllByFacilityID(
                    $this->request->id,
                    isset($this->request->start_date) ? $this->request->start_date : $yesterdayDate,
                    isset($this->request->end_date) ? $this->request->end_date : $yesterdayDate
                );
                $data['filter_start_date'] = (isset($this->request->start_date)) ? $this->request->start_date : null;
                $data['filter_end_date'] = (isset($this->request->end_date)) ? $this->request->end_date : null;
            break;

            //Fetch maintenance history
            case 10:
                $data['interventions'] = InterventionManager::getAllByFacilityID(
                    $this->request->id,
                    isset($this->request->start_date) ? $this->request->start_date : $yesterdayDate,
                    isset($this->request->end_date) ? $this->request->end_date : $yesterdayDate
                );
                $data['filter_start_date'] = (isset($this->request->start_date)) ? $this->request->start_date : null;
                $data['filter_end_date'] = (isset($this->request->end_date)) ? $this->request->end_date : null;
            break;

            //Fetch facilities data files
            case 11:
                $queryString = '';
                if (isset($this->request->year)) $queryString = $this->request->year;
                if (isset($this->request->month)) $queryString .= '/' . $this->request->month;
                if (isset($this->request->day)) $queryString .= '/' . $this->request->day;
                $data['query_string'] = $queryString;
                $data['entries'] = $this->getEntries($this->request->id, $queryString);
            break;

            default:
            break;
        }

        return view('biomet::pages.facility.tabs.' . $tab, [
            'current_tab' => $tab,
            'current_facility' => FacilityManager::getByID($this->request->id),
            'data' => $data,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
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

    public function download_file()
    {
        parent::__construct($this->request);

        if (!$this->canViewFacilityTab($this->request->id, 11)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        return response()->download(env('DATA_FOLDER_PATH') . '/xls/' . implode([$this->request->id, $this->request->year, $this->request->month, $this->request->day, 'data.xlsx'], '/'));
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

    /**
     * @param $facilityID
     * @param string $queryString
     * @return array
     */
    private function getEntries($facilityID, $queryString = '')
    {
        $entries = [];
        $path = realpath(env('DATA_FOLDER_PATH') . '/xls/' . $facilityID . '/' . $queryString);
        foreach (new IteratorIterator(new DirectoryIterator($path)) as $entry) {

            //Folders
            if (!preg_match('/\./', $entry->getPathname())) {
                $entries[] = [
                    'type' => 'folder',
                    'name' => preg_replace('#' . $path . '/#', '', $entry->getPathname())
                ];
            }

            //Files
            if (preg_match('/\.xls/', $entry->getPathname())) {
                $entries[] = [
                    'type' => 'file',
                    'name' => preg_replace('#' . $path . '/#', '', $entry->getPathname()),
                    'link' => route('facility_download_file', ['id' => $facilityID, 'query_string' => $queryString])
                ];
            }
        }

        usort($entries, function($a, $b) {
            return $a['name'] > $b['name'];
        });

        return $entries;
    }

    public function add_intervention()
    {
        return view('biomet::pages.facility.interventions.add', [
            'facility_id' => $this->request->id,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function store_intervention()
    {
        try {
            InterventionManager::createIntervention(
                $this->request->input('facility_id'),
                $this->request->input('event_date'),
                $this->request->input('title'),
                $this->request->input('personal_information'),
                $this->request->input('description')
            );
            $this->request->session()->flash('confirmation', trans('biomet::interventions.add_intervention_success'));

            return redirect()->route('facility_tab', ['id' => $this->request->input('facility_id'), 'tab' => 10]);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.add_intervention_error'));

            return redirect()->route('facility_tab', ['id' => $this->request->input('facility_id'), 'tab' => 10]);
        }
    }

    public function edit_intervention()
    {
        try {
            $intervention = InterventionManager::getByID($this->request->id);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.intervention_not_found_error'));

            return redirect()->route('facility_tab', ['id' => $this->request->input('facility_id'), 'tab' => 10]);
        }

        return view('biomet::pages.facility.interventions.edit', [
            'intervention' => $intervention,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update_intervention()
    {
        try {
            InterventionManager::udpateIntervention(
                $this->request->input('intervention_id'),
                $this->request->input('facility_id'),
                $this->request->input('event_date'),
                $this->request->input('title'),
                $this->request->input('personal_information'),
                $this->request->input('description')
            );
            $this->request->session()->flash('confirmation', trans('biomet::interventions.edit_intervention_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.update_intervention_error'));
        }

        return redirect()->route('interventions_edit', ['id' => $this->request->input('intervention_id')]);
    }

    public function delete_intervention()
    {
        try {
            $interventionID = $this->request->id;
            if ($intervention = InterventionManager::getByID($interventionID)) {
                InterventionManager::deleteIntervention($interventionID);
                $this->request->session()->flash('confirmation', trans('biomet::interventions.delete_intervention_success'));
            } else {
                $this->request->session()->flash('error', trans('biomet::interventions.delete_intervention_error'));
            }
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.delete_intervention_error'));
        }

        return redirect()->route('facility_tab', ['id' => $intervention->facility_id, 'tab' => 10]);
    }
}
<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use DateTime;
use DirectoryIterator;
use Illuminate\Support\Facades\Gate;
use IteratorIterator;
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

    public function tab()
    {
        parent::__construct($this->request);

        $tab = isset($this->request->tab) ? $this->request->tab : 1;
        if (!$this->canViewFacilityTab($this->request->id, $tab)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        $data = [];
        if ($tab == 11) {
            $queryString = '';
            if (isset($this->request->year)) $queryString = $this->request->year;
            if (isset($this->request->month)) $queryString .= '/' . $this->request->month;
            if (isset($this->request->day)) $queryString .= '/' . $this->request->day;
            $data['query_string'] = $queryString;
            $data['entries'] = $this->getEntries($this->request->id, $queryString);
        }

        return view('biomet::pages.facility.tabs.' . $tab, [
            'current_tab' => $tab,
            'current_facility' => FacilityManager::getByID($this->request->id),
            'data' => $data,
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
}
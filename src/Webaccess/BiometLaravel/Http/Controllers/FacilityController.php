<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use DateInterval;
use DateTime;
use DirectoryIterator;
use Illuminate\Support\Facades\Gate;
use IteratorIterator;
use Webaccess\BiometLaravel\Services\AlarmManager;
use Webaccess\BiometLaravel\Services\EquipmentManager;
use Webaccess\BiometLaravel\Services\FacilityManager;
use Webaccess\BiometLaravel\Services\InterventionManager;

class FacilityController extends BaseController
{
    public function index()
    {
        parent::__construct($this->request);
        ini_set('memory_limit', -1);
        
        return view('biomet::pages.facility.index', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'current_facility' => FacilityManager::getByID($this->request->id),
            'alarms' => AlarmManager::getAllByFacilityID($this->request->id, null, null, false, 5),

            'avg_igp_last_24h' => $this->getAverageValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('IGP')),
            'avg_igp_last_7_days' => $this->getAverageValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-7 days' ))), new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('IGP')),
            'avg_igp_last_month' => $this->getAverageValue($this->request->id, (new DateTime(date('Y-m-d', strtotime( '-1 days' ))))->sub(new DateInterval('P1M')), new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('IGP')),
            'avg_igp_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('AVG_IGP_CURRENT_YEAR')),

            'avg_ap0201_last_24h' => $this->getAverageValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('AP0201_H2S')),
            'avg_ap0202_last_24h' => $this->getAverageValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('AP0202_H2S')),
            'avg_ap0203_last_24h' => $this->getAverageValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('AP0203_H2S')),

            'sum_ft0101f_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('SUM_FT0101F_CURRENT_YEAR')),
            'sum_ft0102f_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('SUM_FT0102F_CURRENT_YEAR')),

            'sum_conso_elec_install_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('SUM_CONSO_ELEC_INSTALL_CURRENT_YEAR')),

            'qte_biomethane_injecte_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('QTE_BIOMETHANE_INJECTE')),
            'pcs_biomethane_injecte_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('SUM_PCS_BIOMETHANE_INJECTE_CURRENT_YEAR')),
            'qte_biomethane_non_conforme_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('QTE_BIOMETHANE_NON_CONFORME')),
            'pcs_biomethane_non_conforme_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('SUM_PCS_BIOMETHANE_NON_CONFORME_CURRENT_YEAR')),

            'heures_en_fonctionnement_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('HEURES_EN_FONCTIONNEMENT_CURRENT_YEAR')),
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
        $yesterdayDate = (new DateTime())->sub(new DateInterval('P1D'));

        switch ($tab) {

            //Fetch equipments list
            case 8:
                $data['equipments'] = EquipmentManager::getAllByFacilityID($this->request->id);
            break;

            //Fetch alarms log
            case 9:
                $data['alarms'] = AlarmManager::getAllByFacilityID(
                    $this->request->id,
                    isset($this->request->start_date) ? DateTime::createFromFormat('d/m/Y', $this->request->start_date) : $yesterdayDate,
                    isset($this->request->end_date) ? DateTime::createFromFormat('d/m/Y', $this->request->end_date) : $yesterdayDate
                );
                $data['filter_start_date'] = (isset($this->request->start_date)) ? DateTime::createFromFormat('d/m/Y', $this->request->start_date)->format('d/m/Y') : null;
                $data['filter_end_date'] = (isset($this->request->end_date)) ? DateTime::createFromFormat('d/m/Y', $this->request->end_date)->format('d/m/Y') : null;
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
                $data['entries'] = $this->getFacilitiesDataFiles($this->request->id, $queryString);
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
        parent::__construct($this->request);
        ini_set('memory_limit', -1);

        return view('biomet::pages.facility.includes.graph', [
            'container_id' => $this->request->container_id,
            'title' => $this->request->title,
            'series' => json_encode(FacilityManager::getData(DateTime::createFromFormat('d/m/Y', $this->request->start_date), DateTime::createFromFormat('d/m/Y', $this->request->end_date), $this->request->facility_id, $this->request->keys, $this->request->legend)),
        ])->render();
    }

    public function excel()
    {
        $data = FacilityManager::getData(DateTime::createFromFormat('d/m/Y', $this->request->start_date), DateTime::createFromFormat('d/m/Y', $this->request->end_date), $this->request->facility_id, explode(',', $this->request->keys), explode(',', $this->request->legend));
        $file = FacilityManager::createExcelFile($data);

        return response()->download($file);
    }

    public function group_excel()
    {
        if ($file = FacilityManager::groupExcelFiles(DateTime::createFromFormat('d/m/Y', $this->request->start_date), DateTime::createFromFormat('d/m/Y', $this->request->end_date), $this->request->facility_id)) {

            return response()->download($file);
        }

        $this->request->session()->flash('error', trans('biomet::facilities.error_excel_grouping_no_files'));
        return redirect()->route('facility_tab', ['id' => $this->request->facility_id, 'tab' => 11]);
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
    private function getFacilitiesDataFiles($facilityID, $queryString = '')
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
            if (preg_match('/data\.xls/', $entry->getPathname())) {
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

    /**
     * @param $facilityID
     * @param $startDate
     * @param $endDate
     * @param $keys
     * @return float|int
     */
    private function getAverageValue($facilityID, $startDate, $endDate, $keys)
    {
        $data = FacilityManager::getData($startDate, $endDate, $facilityID, $keys, false);
        $total = 0;
        $count = 0;

        foreach ($data as $file) {
            foreach ($file['data'] as $value) {
                $total += $value[1];
                $count ++;
            }
        }

        return ($count > 0) ? round($total / $count, 1) : 0;
    }

    /**
     * @param $facilityID
     * @param $date
     * @param $keys
     * @return int
     */
    private function getValue($facilityID, $date, $keys)
    {
        $data = FacilityManager::getData($date, $date, $facilityID, $keys, false);
        foreach ($data as $file) {
            foreach ($file['data'] as $value) {
                return $value[1];
            }
        }

        return 0;
    }
}
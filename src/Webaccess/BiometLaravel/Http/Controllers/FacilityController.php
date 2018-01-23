<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use DateInterval;
use DateTime;
use DirectoryIterator;
use Illuminate\Support\Facades\Cache;
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

            'qte_biomethane_injecte_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('QTE_BIOMETHANE_INJECTE_CURRENT_YEAR')),
            'qte_biomethane_non_conforme_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('QTE_BIOMETHANE_NON_CONFORME_CURRENT_YEAR')),

            'heures_en_fonctionnement_current_year' => $this->getValue($this->request->id, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('HEURES_EN_FONCTIONNEMENT_CURRENT_YEAR')),
        ]);
    }

    public function tab()
    {
        setlocale(LC_ALL, 'fr_FR.UTF8');

        parent::__construct($this->request);
        $tab = isset($this->request->tab) ? $this->request->tab : 1;
        if (!$this->canViewFacilityTab($this->request->id, $tab)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $this->request->id]);
        }

        $data = [];
        $yesterdayDate = (new DateTime())->sub(new DateInterval('P1D'));

        switch ($tab) {

            //Débit
            case 1:
                switch ($this->request->id) {
                    case env('TERRAGREAU_FACILITY_ID'):
                        $data['keys'] = 'FT0101F,FT0102F,FT0201F,QV_BIO_EA';
                        $data['legends'] = 'Biogaz brut (Nm<sup>3</sup>/h),Biométhane (Nm<sup>3</sup>/h),Chaudière (Nm<sup>3</sup>/h),Section amines (Nm<sup>3</sup>/h)';
                    break;

                    case env('VIENNE_FACILITY_ID'):
                        $data['keys'] = 'FT0101F,FT0102F,FT0201F';
                        $data['legends'] = 'Biogaz brut (Nm<sup>3</sup>/h),Biométhane (Nm<sup>3</sup>/h),Biogaz membranes (Nm<sup>3</sup>/h)';
                    break;
                }
            break;

            //Volume
            case 2:
                switch ($this->request->id) {
                    case env('TERRAGREAU_FACILITY_ID'):
                        $data['keys'] = 'FT0101F_VOLUME,FT0102F_VOLUME,FT0201F_VOLUME,QV_BIO_EA_VOLUME';
                        $data['legends'] = 'Biogaz brut (Nm<sup>3</sup>),Biométhane (Nm<sup>3</sup>),Chaudière (Nm<sup>3</sup>),Section amines (Nm<sup>3</sup>)';
                    break;

                    case env('VIENNE_FACILITY_ID'):
                        $data['keys'] = 'FT0101F_VOLUME,FT0102F_VOLUME,FT0201F_VOLUME';
                        $data['legends'] = 'Biogaz brut (Nm<sup>3</sup>),Biométhane (Nm<sup>3</sup>),Biogaz membranes (Nm<sup>3</sup>)';
                    break;
                }
            break;

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

            //Fetch monthly report
            case 12:
                $year = (isset($this->request->year)) ? $this->request->year : date('Y');

                $data['year'] = $year;

                $series = [];
                $months = [];
                for ($m = 1; $m <= 12; $m++) {
                    $startOfMonth = new DateTime($year . '-' . $m . '-01');
                    $endOfMonth = clone $startOfMonth;
                    $endOfMonth = $endOfMonth->setDate($year, $m, $startOfMonth->format('t'));
                    $totals = $this->getMonthlySum($this->request->id, $startOfMonth, $endOfMonth, array('FT0101F_VOLUME', 'FT0102F_VOLUME', 'FT0201F_VOLUME', 'QV_BIO_EA_VOLUME'));

                    $consommation_electrique = $this->getPowerConsumptionAverageValue($this->request->id, $startOfMonth, $endOfMonth);

                    $month = [
                        'name' => strftime("%B", $startOfMonth->getTimestamp()),
                        'biogaz' => isset($totals['FT0101F_VOLUME']) ? $totals['FT0101F_VOLUME'] : 0,
                        'biomethane' => isset($totals['FT0102F_VOLUME']) ? $totals['FT0102F_VOLUME'] : 0,
                        'consommation_electrique' => $consommation_electrique
                    ];

                    if ($this->request->id == env('TERRAGREAU_FACILITY_ID')) {
                        $month['amines'] = isset($totals['QV_BIO_EA_VOLUME']) ? $totals['QV_BIO_EA_VOLUME'] : 0;
                        $month['chaudiere'] = isset($totals['FT0201F_VOLUME']) ? $totals['FT0201F_VOLUME'] : 0;
                    } elseif ($this->request->id == env('VIENNE_FACILITY_ID')) {
                        $month['membranes'] = isset($totals['FT0201F_VOLUME']) ? $totals['FT0201F_VOLUME'] : 0;
                    }

                    $months[]= $month;

                    $series[0]['data'][]= [$startOfMonth->getTimestamp() * 1000, isset($totals['FT0101F_VOLUME']) ? $totals['FT0101F_VOLUME'] : 0];
                    $series[1]['data'][]= [$startOfMonth->getTimestamp() * 1000, isset($totals['FT0102F_VOLUME']) ? $totals['FT0102F_VOLUME'] : 0];
                    $series[2]['data'][]= [$startOfMonth->getTimestamp() * 1000, isset($totals['FT0201F_VOLUME']) ? $totals['FT0201F_VOLUME'] : 0];
                    if ($this->request->id == env('TERRAGREAU_FACILITY_ID')) {
                        $series[3]['data'][] = [$startOfMonth->getTimestamp() * 1000, isset($totals['QV_BIO_EA_VOLUME']) ? $totals['QV_BIO_EA_VOLUME'] : 0];
                    }
                    $series[4]['data'][]= [$startOfMonth->getTimestamp() * 1000, $consommation_electrique];
                }

                $series[0]['name'] = 'Biogaz brut (Nm<sup>3</sup>)';
                $series[1]['name'] = 'Biométhane (Nm<sup>3</sup>)';
                if ($this->request->id == env('TERRAGREAU_FACILITY_ID')) {
                    $series[2]['name'] = 'Chaudière (Nm<sup>3</sup>)';
                    $series[3]['name'] = 'Section amines (Nm<sup>3</sup>)';
                } elseif ($this->request->id == env('VIENNE_FACILITY_ID')) {
                    $series[2]['name'] = 'Biogaz Membranes (Nm<sup>3</sup>)';
                }
                $series[4]['name'] = 'Consommation électrique (kWh)';

                $data['months'] = $months;
                $data['series'] = json_encode(array_values($series));
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

        $startDate = DateTime::createFromFormat('d/m/Y', $this->request->start_date);
        $endDate = DateTime::createFromFormat('d/m/Y', $this->request->end_date);
        $cacheKey = implode('#', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d'), $this->request->facility_id, implode('-', $this->request->keys)]);

        $series = [];
        if (!Cache::has($cacheKey)) {
            $series = json_encode(FacilityManager::getData($startDate, $endDate, $this->request->facility_id, $this->request->keys, $this->request->legend));
            Cache::put($cacheKey, $series, 1440);
        } else {
            $series = Cache::get($cacheKey);
        }

        return view('biomet::pages.facility.includes.graph', [
            'container_id' => $this->request->container_id,
            'title' => $this->request->title,
            'series' => $series,
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

        $facilityID = $this->request->id;

        if (!$this->canViewFacilityTab($facilityID, 11)) {
            $this->request->session()->flash('error', trans('biomet::generic.no_permission_error'));

            return redirect()->route('facility', ['id' => $facilityID]);
        }

        $filePath = env('DATA_FOLDER_PATH') . '/xls/' . implode([$facilityID, $this->request->year, $this->request->month, $this->request->day, FacilityManager::getDataFilePattern($facilityID) . '-'. $this->request->year . '-' . $this->request->month . '-' . $this->request->day . '.xlsx'], '/');
        $fileName = FacilityManager::getDataFilePattern($facilityID) . '-' . $this->request->year . $this->request->month . $this->request->day . '.xlsx';

        if (!file_exists($filePath)) {
            $this->request->session()->flash('error', trans('biomet::generic.file_doesnt_exists'));

            return redirect()->route('facility_tab', ['id' => $facilityID, 'tab' => 11]);
        }

        return response()->download($filePath, $fileName);
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
            if (preg_match('/' . FacilityManager::getDataFilePattern($facilityID) . '([0-9\-]*)\.xlsx' . '/', $entry->getPathname())) {
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
     * @param $startDate
     * @param $endDate
     * @param $keys
     * @return float|int
     */
    private function getMonthlySum($facilityID, $startDate, $endDate, $keys)
    {
        $data = FacilityManager::getData($startDate, $endDate, $facilityID, $keys, false);
        $totals = [];

        foreach ($data as $keyData) {
            $key = $keyData['name'];

            if (!isset($totals[$key]))
                $totals[$key] = 0;

            foreach ($keyData['data'] as $i => $day) {
                if (isset($day[1])) {
                    $totals[$key] += $day[1];
                }
            }
        }

        return $totals;
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

    /**
     * @param $facilityID
     * @param $startDate
     * @param $endDate
     * @return float
     */
    private function getPowerConsumptionAverageValue($facilityID, $startDate, $endDate)
    {
        $data = FacilityManager::getData($startDate, $endDate, $facilityID, array('CONSO_ELEC_INSTAL_AVG_DAILY_INDICATOR'), false);
        $total = array_sum($data);

        return round($total * 24);
    }
}

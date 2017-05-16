<?php

namespace Webaccess\BiometLaravel\Services;

use DateInterval;
use DateTime;
use DateTimeZone;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Reader_Excel2007;
use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Facility;

class FacilityManager
{

    public static function getAll($itemsPerPage = false, $clientID = null, $clientName = null, $orderBy = null, $order = null)
    {
        $facilities = Facility::orderBy($orderBy ? $orderBy : 'created_at', $order ? $order : 'asc');

        if ($clientName)
            $facilities->where('name', 'LIKE', '%' . $clientName . '%');

        if ($clientID)
            $facilities->where('client_id', '=', $clientID);

        return ($itemsPerPage) ? $facilities->paginate($itemsPerPage) : $facilities->get();
    }

    public static function getByClient($clientID)
    {
        return Facility::where('client_id', '=', $clientID)->get();
    }

    public static function getByID($facilityID)
    {
        return Facility::find($facilityID);
    }

    /**
     * @param $name
     * @param $longitude
     * @param $latitude
     * @param $address
     * @param $city
     * @param $department
     * @param $country
     * @param $clientID
     * @param $technology
     * @param $serialNumber
     * @param $startupDate
     * @param array $tabs
     * @return Facility
     */
    public static function createFacility($name, $longitude, $latitude, $address, $city, $department, $country, $clientID, $technology, $serialNumber, $startupDate, $tabs = [])
    {
        $facility = new Facility();
        $facility->id = Uuid::uuid4()->toString();
        $facility->name = $name;
        $facility->longitude = $longitude;
        $facility->latitude = $latitude;
        $facility->address = $address;
        $facility->city = $city;
        $facility->department = $department;
        $facility->country = $country;
        $facility->client_id = $clientID;
        $facility->technology = $technology;
        $facility->serial_number = $serialNumber;
        $facility->startup_date = $startupDate;
        $facility->tabs = $tabs;

        $facilityID = $facility->save();

        return $facilityID;
    }

    /**
     * @param $facilityID
     * @param $name
     * @param $longitude
     * @param $latitude
     * @param $address
     * @param $city
     * @param $department
     * @param $country
     * @param $clientID
     * @param $technology
     * @param $serialNumber
     * @param $startupDate
     * @param array $tabs
     * @return bool
     */
    public static function udpateFacility($facilityID, $name, $longitude, $latitude, $address, $city, $department, $country, $clientID, $technology, $serialNumber, $startupDate, $tabs = [])
    {
        if ($facility = Facility::find($facilityID)) {
            $facility->name = $name;
            $facility->longitude = $longitude;
            $facility->latitude = $latitude;
            $facility->address = $address;
            $facility->city = $city;
            $facility->department = $department;
            $facility->country = $country;
            $facility->client_id = $clientID;
            $facility->technology = $technology;
            $facility->serial_number = $serialNumber;
            $facility->startup_date = $startupDate;
            $facility->tabs = $tabs;
            $facility->save();

            return true;
        }

        return false;
    }

    /**
     * @param $facilityID
     * @return bool
     */
    public static function deleteFacility($facilityID)
    {
        if ($facility = Facility::find($facilityID)) {
            $facility->delete();

            return true;
        }

        return false;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param $facilityID
     * @param $keys
     * @param $legend
     * @return mixed
     */
    public static function getData(DateTime $startDate, DateTime $endDate, $facilityID, $keys, $legend = [])
    {
        $series = [];
        $fileData = self::fetchData($startDate, $endDate, $facilityID);

        foreach ($keys as $i => $key) {
            $keyData = [];

            //Daily indicator
            if (preg_match('/DAILY_INDICATOR/', $key)) {
                $result = [];
                if (is_array($fileData) && sizeof($fileData) > 0) {
                    foreach ($fileData as $data) {
                        if (isset($data->$key))
                            $result[$data->timestamp] = $data->$key;
                    }
                }

                return $result;
            }

            //Average serie
            elseif (preg_match('/_AVG/', $key)) {
                $allData = [];
                if (is_array($fileData) && sizeof($fileData) > 0) {
                    $avg = self::calculateAverage($fileData, $key, $allData);

                    foreach ($fileData as $data) {
                        $keyData[]= [$data->timestamp * 1000, $avg];
                    }
                }

            //Standard serie
            } else {
                if (is_array($fileData) && sizeof($fileData) > 0) {
                    foreach ($fileData as $data) {
                        if (isset($data->$key)) {
                            if (!is_numeric($data->$key))
                                $data->$key = null;
                            $keyData[] = [$data->timestamp * 1000, $data->$key];
                        }
                    }
                }
            }

            $series[] = [
                'name' => isset($legend[$i]) ? $legend[$i] : $key,
                'data' => $keyData
            ];
        }

        return $series;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param $facilityID
     * @return array
     */
    private static function fetchData(DateTime $startDate, DateTime $endDate, $facilityID)
    {
        $date = clone $startDate;
        $jsonFiles = [];
        $fileData = [];

        while ($date <= $endDate) {
            $jsonFile = env('DATA_FOLDER_PATH') . '/json/' . $facilityID . '/' . $date->format('Y/m/d') . '/data.json';
            if (file_exists($jsonFile)) {
                $jsonFiles[] = $jsonFile;
            }
            $date->add(new DateInterval('P1D'));
        }

        foreach ($jsonFiles as $jsonFile) {
            $data = json_decode(file_get_contents($jsonFile));

            foreach ($data as $d) {
                $fileData[] = $d;
            }
        }

        return $fileData;
    }

    /**
     * @param $fileData
     * @param $key
     * @param $allData
     * @return array
     */
    private static function calculateAverage($fileData, $key, $allData)
    {
        foreach ($fileData as $data) {
            $targetedKey = preg_replace('/_AVG/', '', $key);
            if (isset($data->$targetedKey))
                $allData[] = $data->$targetedKey;
        }

        return count($allData) > 0 ? array_sum($allData) / count($allData) : 0;
    }

    public static function createExcelFile($data)
    {
        $file = env('DATA_FOLDER_PATH') . '/temp/data-' . time() . '.xlsx';

        $objPHPExcel = new PHPExcel();

        $length = sizeof($data[0]['data']);

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Date');

        for ($col = 0; $col < sizeof($data); $col++) {
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col + 1, 1)->setValue(strip_tags($data[$col]['name']));

            for ($row = 0; $row < $length; $row++) {
                $dateTime = (new DateTime())->setTimestamp($data[0]['data'][$row][0] / 1000);
                $dateTime->setTimezone(new DateTimeZone('Europe/Paris'));
                $objPHPExcel->getActiveSheet()->setCellValue('A' . ($row + 2), $dateTime->format('d/m/Y H:i:s'));
                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col + 1, ($row + 2))->setValue($data[$col]['data'][$row][1]);
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($file);

        return $file;
    }

    public static function groupExcelFiles($startDate, $endDate, $facilityID)
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $date = clone $startDate;

        $xlsFiles = [];
        while ($date <= $endDate) {
            $xlsFile = env('DATA_FOLDER_PATH') . '/xls/' . $facilityID . '/' . $date->format('Y/m/d') . '/data.xlsx';
            if (file_exists($xlsFile)) {
                $xlsFiles[] = $xlsFile;
            }
            $date->add(new DateInterval('P1D'));
        }

        $baseFile = array_shift($xlsFiles);
        if ($baseFile) {
            $objReader = new PHPExcel_Reader_Excel2007();
            $objReader->setReadDataOnly(true);
            $baseObjPHPExcel = $objReader->load($baseFile);

            foreach ($xlsFiles as $i => $xlsFile) {
                $objPHPExcel = $objReader->load($xlsFile);
                foreach ($objPHPExcel->getAllSheets() as $sheetIndex => $sheet) {
                    $baseObjPHPExcel->setActiveSheetIndex($sheetIndex);
                    $startingRow = ($sheetIndex == 9) ? 2 : 3;
                    $findEndDataRow = $sheet->getHighestRow();
                    $findEndDataColumn = $sheet->getHighestColumn();
                    $findEndData = $findEndDataColumn . $findEndDataRow;
                    $fileData = $sheet->rangeToArray('A' . $startingRow . ':' . $findEndData);
                    $appendStartRow = $baseObjPHPExcel->getSheet($sheetIndex)->getHighestRow() + 1;
                    $baseObjPHPExcel->getActiveSheet()->fromArray($fileData, null, 'A' . $appendStartRow);
                }
                $objPHPExcel->disconnectWorksheets();
                unset($objPHPExcel);
            }

            $file = env('DATA_FOLDER_PATH') . '/temp/data-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '-' . time() . '.xlsx';

            $objWriter = PHPExcel_IOFactory::createWriter($baseObjPHPExcel, 'Excel2007');
            $objWriter->save($file);

            return $file;
        }

        return false;
    }
}

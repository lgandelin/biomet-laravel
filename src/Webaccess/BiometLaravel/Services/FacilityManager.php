<?php

namespace Webaccess\BiometLaravel\Services;

use DateInterval;
use DateTime;
use PHPExcel;
use PHPExcel_IOFactory;
use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Facility;

class FacilityManager
{

    public static function getAll($itemsPerPage = false, $clientID = null, $clientName = null)
    {
        $facilities = Facility::orderBy('created_at');

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
     * @param bool $legend
     * @return mixed
     */
    public static function getData(DateTime $startDate, DateTime $endDate, $facilityID, $keys, $legend = true)
    {
        $series = [];
        $fileData = self::fetchData($startDate, $endDate, $facilityID);

        foreach ($keys as $key) {
            $keyData = [];

            //Average serie
            if (preg_match('/_AVG/', $key)) {
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
                'name' => $legend ? self::getLegendFromKey($key) : $key,
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

            /*TEMP : A SUPPRIMER
            usort($data, function ($a, $b)
            {
                return ($a->timestamp < $b->timestamp) ? -1 : 1;
            });
            //TEMP : A SUPPRIMER*/

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
            $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col + 1, 1)->setValue($data[$col]['name']);

            for ($row = 0; $row < $length; $row++) {
                $dateTime = (new DateTime())->setTimestamp($data[0]['data'][$row][0] / 1000);
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
        $baseObjPHPExcel = PHPExcel_IOFactory::load($baseFile);

        foreach ($xlsFiles as $i => $xlsFile) {

            $objPHPExcel = PHPExcel_IOFactory::load($xlsFile);
            foreach ($objPHPExcel->getAllSheets() as $sheetIndex => $sheet) {
                $startingRow = ($sheetIndex == 9) ? 2 : 3;
                $findEndDataRow = $sheet->getHighestRow();
                $findEndDataColumn = $sheet->getHighestColumn();
                $findEndData = $findEndDataColumn . $findEndDataRow;
                $fileData = $sheet->rangeToArray('A' . $startingRow . ':' . $findEndData);
                $appendStartRow = $baseObjPHPExcel->getSheet($sheetIndex)->getHighestRow() + 1;
                $baseObjPHPExcel->getSheet($sheetIndex)->fromArray($fileData, null, 'A' . $appendStartRow);
            }
        }

        $file = env('DATA_FOLDER_PATH') . '/temp/data-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d'). '-' . time() . '.xlsx';

        $objWriter = PHPExcel_IOFactory::createWriter($baseObjPHPExcel, 'Excel2007');
        $objWriter->save($file);

        return $file;
    }

    private static function getLegendFromKey($key)
    {
        $legends = [
            'FT0101F' => 'Biogaz brut (Nm<sup>3</sup>/h)',
            'FT0102F' => 'Biométhane (Nm<sup>3</sup>/h)',
            'FT0101F_VOLUME' => 'Biogaz brut (Nm<sup>3</sup>)',
            'FT0102F_VOLUME' => 'Biométhane (Nm<sup>3</sup>)',

            'AP0101_CH4' => 'CH<sub>4</sub> (%)',
            'AP0101_CO2' => 'CO<sub>2</sub> (%)',
            'AP0101_H2O' => 'H<sub>2</sub>O (%HR)',
            'AP0101_H2S' => 'H<sub>2</sub>S (ppm)',
            'AP0101_O2' => 'O<sub>2</sub> (%)',

            'AP0201_CH4' => 'CH<sub>4</sub> (%)',
            'AP0201_CO2' => 'CO<sub>2</sub> (%)',
            'AP0201_H2O' => 'H<sub>2</sub>O (%HR)',
            'AP0201_H2S' => 'H<sub>2</sub>S (ppm)',
            'AP0201_O2' => 'O<sub>2</sub> (%)',

            'IGP' => 'IGP (-)',
            'IGP_AVG' => 'IGP moyen (-)',

            'CONSO_ELEC_INSTAL' => 'CONSO_ELEC_INSTAL (kWh)',
            'CONSO_ELEC_INSTAL_AVG' => 'CONSO_ELEC_INSTAL_AVG (kWh)',
            'CONSO_ELEC_CHAUD' => 'CONSO_ELEC_CHAUD (kWh)',
            'CONSO_ELEC_CHAUD_AVG' => 'CONSO_ELEC_CHAUD_AVG (kWh)',
            'CONSO_ELEC_PEC' => 'CONSO_ELEC_PEC (kWh)',
            'CONSO_ELEC_PEC_AVG' => 'CONSO_ELEC_PEC_AVG (kWh)',

            'Q_DIGEST' => 'Q_DIGEST (kW)',
        ];

        return isset($legends[$key]) ? $legends[$key] : $key;
    }
}

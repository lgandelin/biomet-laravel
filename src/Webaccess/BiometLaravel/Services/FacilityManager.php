<?php

namespace Webaccess\BiometLaravel\Services;

use DateInterval;
use DateTime;
use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Facility;

class FacilityManager
{

    public static function getAll($paginate = true, $clientID = null, $clientName = null)
    {
        $facilities = Facility::orderBy('created_at');

        if ($clientName)
            $facilities->where('name', 'LIKE', '%' . $clientName . '%');

        if ($clientID)
            $facilities->where('client_id', '=', $clientID);

        return ($paginate === true) ? $facilities->paginate(10) : $facilities->get();
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
     * @param $clientID
     * @return Facility
     */
    public static function createFacility($name, $longitude, $latitude, $address, $city, $department, $clientID)
    {
        $facility = new Facility();
        $facility->id = Uuid::uuid4()->toString();
        $facility->name = $name;
        $facility->longitude = $longitude;
        $facility->latitude = $latitude;
        $facility->address = $address;
        $facility->city = $city;
        $facility->department = $department;
        $facility->client_id = $clientID;

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
     * @param $clientID
     * @return bool
     */
    public static function udpateFacility($facilityID, $name, $longitude, $latitude, $address, $city, $department, $clientID)
    {
        if ($facility = Facility::find($facilityID)) {
            $facility->name = $name;
            $facility->longitude = $longitude;
            $facility->latitude = $latitude;
            $facility->address = $address;
            $facility->city = $city;
            $facility->department = $department;
            $facility->client_id = $clientID;
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
     * @return mixed
     */
    public static function getData(DateTime $startDate, DateTime $endDate, $facilityID, $keys)
    {
        $series = [];
        $fileData = self::fetchData($startDate, $endDate, $facilityID);

        foreach ($keys as $key) {
            $keyData = [];

            if (is_array($fileData) && sizeof($fileData) > 0) {
                foreach ($fileData as $data) {
                    $keyData[] = [$data->timestamp * 1000, $data->$key];
                }
            }

            $series[] = [
                'name' => $key,
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
            $jsonFile = env('DATA_FOLDER_PATH') . '/sites/' . $facilityID . '/' . $date->format('Y/m/d') . '/data.json';
            if (file_exists($jsonFile)) {
                $jsonFiles[] = $jsonFile;
            }
            $date->add(new DateInterval('P1D'));
        }

        foreach ($jsonFiles as $jsonFile) {
            $data = json_decode(file_get_contents($jsonFile));

            //TEMP : A SUPPRIMER
            usort($data, function ($a, $b)
            {
                return ($a->timestamp < $b->timestamp) ? -1 : 1;
            });
            //TEMP : A SUPPRIMER

            foreach ($data as $d) {
                $fileData[] = $d;
            }
        }

        return $fileData;
    }
}
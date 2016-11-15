<?php

namespace Webaccess\BiometLaravel\Services;

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

    public static function getData()
    {
        return [
            0 => [
                'name' => 'Débit biogaz brut',
                'data' => [
                    [1466209800000,0.000000],
                    [1466213400000,0.000000],
                    [1466217000000,0.000000],
                    [1466220600000,0.000000],
                    [1466224200000,6.917000],
                    [1466227800000,47.867000],
                    [1466231400000,66.650000],
                    [1466235000000,114.067000],
                    [1466238600000,162.083000],
                    [1466242200000,466.933000],
                    [1466245800000,922.250000],
                    [1466249400000,1003.983000],
                    [1466253000000,769.450000],
                    [1466256600000,987.167000],
                    [1466260200000,750.250000],
                    [1466263800000,526.433000],
                    [1466267400000,366.267000],
                    [1466271000000,132.000000],
                    [1466274600000,43.733000],
                    [1466278200000,3.733000],
                    [1466281800000,0.000000],
                    [1466285400000,0.000000],
                    [1466289000000,0.000000],
                    [1466292600000,0.000000]
                ]
            ],
            1 => [
                'name' => 'Débit biogaz amine',
                'data' => [
                    [1466209800000,4.351000],
                    [1466213400000,4.350000],
                    [1466217000000,4.356000],
                    [1466220600000,4.467000],
                    [1466224200000,12.098000],
                    [1466227800000,53.983000],
                    [1466231400000,73.084000],
                    [1466235000000,122.115000],
                    [1466238600000,170.019000],
                    [1466242200000,482.560000],
                    [1466245800000,953.142000],
                    [1466249400000,1040.993000],
                    [1466253000000,798.507000],
                    [1466256600000,1025.654000],
                    [1466260200000,783.921000],
                    [1466263800000,554.734000],
                    [1466267400000,389.635000],
                    [1466271000000,143.175000],
                    [1466274600000,51.273000],
                    [1466278200000,8.859000],
                    [1466281800000,4.469000],
                    [1466285400000,4.479000],
                    [1466289000000,4.474000],
                    [1466292600000,4.462000]
                ]
            ],
            2 => [
                'name' => 'Débit biogaz vers chaudière',
                'data' => [
                    [1466209800000,6.351000],
                    [1466213400000,6.350000],
                    [1466217000000,6.356000],
                    [1466220600000,6.467000],
                    [1466224200000,15.098000],
                    [1466227800000,69.983000],
                    [1466231400000,103.084000],
                    [1466235000000,152.115000],
                    [1466238600000,190.019000],
                    [1466242200000,582.560000],
                    [1466245800000,1153.142000],
                    [1466249400000,1240.993000],
                    [1466253000000,998.507000],
                    [1466256600000,1225.654000],
                    [1466260200000,683.921000],
                    [1466263800000,554.734000],
                    [1466267400000,289.635000],
                    [1466271000000,103.175000],
                    [1466274600000,41.273000],
                    [1466278200000,15.859000],
                    [1466281800000,10.469000],
                    [1466285400000,6.479000],
                    [1466289000000,6.474000],
                    [1466292600000,6.462000]
                ]
            ],
            3 => [
                'name' => 'Débit biogaz vers chaudière',
                'data' => [
                    [1466209800000,3.351000],
                    [1466213400000,3.350000],
                    [1466217000000,4.356000],
                    [1466220600000,5.467000],
                    [1466224200000,8.098000],
                    [1466227800000,43.983000],
                    [1466231400000,63.084000],
                    [1466235000000,102.115000],
                    [1466238600000,150.019000],
                    [1466242200000,302.560000],
                    [1466245800000,653.142000],
                    [1466249400000,840.993000],
                    [1466253000000,698.507000],
                    [1466256600000,625.654000],
                    [1466260200000,583.921000],
                    [1466263800000,454.734000],
                    [1466267400000,289.635000],
                    [1466271000000,143.175000],
                    [1466274600000,81.273000],
                    [1466278200000,15.859000],
                    [1466281800000,16.469000],
                    [1466285400000,14.479000],
                    [1466289000000,14.474000],
                    [1466292600000,14.462000]
                ]
            ],
            4 => [
                'name' => 'Débit biométhane',
                'data' => [
                    [1466209800000,9.351000],
                    [1466213400000,9.350000],
                    [1466217000000,9.356000],
                    [1466220600000,15.467000],
                    [1466224200000,28.098000],
                    [1466227800000,63.983000],
                    [1466231400000,83.084000],
                    [1466235000000,202.115000],
                    [1466238600000,250.019000],
                    [1466242200000,402.560000],
                    [1466245800000,853.142000],
                    [1466249400000,1040.993000],
                    [1466253000000,1298.507000],
                    [1466256600000,1225.654000],
                    [1466260200000,1083.921000],
                    [1466263800000,654.734000],
                    [1466267400000,589.635000],
                    [1466271000000,243.175000],
                    [1466274600000,41.273000],
                    [1466278200000,10.859000],
                    [1466281800000,2.469000],
                    [1466285400000,2.479000],
                    [1466289000000,2.474000],
                    [1466292600000,2.462000]
                ]
            ]
        ];
    }
}
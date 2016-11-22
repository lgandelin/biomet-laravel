<?php

namespace Webaccess\BiometLaravel\Services;

use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Intervention;

class InterventionManager
{
    public static function getAllByFacilityID($facilityID, $startDate = null, $endDate = null, $paginate = true)
    {
        $interventions = Intervention::where('facility_id', '=', $facilityID)->orderBy('event_date', 'desc');

        if ($startDate) {
            $interventions->where('event_date', '>=', $startDate);
        }

        if ($endDate) {
            $endDate = (new \DateTime($endDate))->add(new \DateInterval('P1D'))->format('Y-m-d H:i:s');
            $interventions->where('event_date', '<=', $endDate);
        }

        return ($paginate) ? $interventions->paginate(10) : $interventions->get();
    }

    public static function getByID($interventionID)
    {
        return Intervention::find($interventionID);
    }

    /**
     * @param $facilityID
     * @param $eventDate
     * @param $title
     * @param $personalInformation
     * @param $description
     * @return Intervention
     */
    public static function createIntervention($facilityID, $eventDate, $title, $personalInformation, $description)
    {
        $intervention = new Intervention();
        $intervention->id = Uuid::uuid4()->toString();
        $intervention->facility_id = $facilityID;
        $intervention->event_date = $eventDate;
        $intervention->title = $title;
        $intervention->personal_information = $personalInformation;
        $intervention->description = $description;

        $intervention->save();

        return $intervention->id;
    }

    /**
     * @param $interventionID
     * @param $facilityID
     * @param $eventDate
     * @param $title
     * @param $personalInformation
     * @param $description
     * @return bool
     */
    public static function udpateIntervention($interventionID, $facilityID, $eventDate, $title, $personalInformation, $description)
    {
        if ($intervention = Intervention::find($interventionID)) {

            $intervention->facility_id = $facilityID;
            $intervention->event_date = $eventDate;
            $intervention->title = $title;
            $intervention->personal_information = $personalInformation;
            $intervention->description = $description;
            $intervention->save();

            return true;
        }

        return false;
    }

    /**
     * @param $interventionID
     * @return bool
     */
    public static function deleteIntervention($interventionID)
    {
        if ($intervention = Intervention::find($interventionID)) {
            $intervention->delete();

            return true;
        }

        return false;
    }
}
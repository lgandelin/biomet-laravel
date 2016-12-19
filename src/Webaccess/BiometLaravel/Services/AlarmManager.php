<?php

namespace Webaccess\BiometLaravel\Services;

use DateTime;
use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Alarm;

class AlarmManager
{
    public static function getAllByFacilityID($facilityID, DateTime $startDate = null, DateTime $endDate = null, $paginate = true)
    {
        $alarms = Alarm::where('facility_id', '=', $facilityID)->orderBy('event_date', 'desc');

        if ($startDate) {
            $alarms->where('event_date', '>=', $startDate->format('Y-m-d H:i:s'));
        }

        if ($endDate) {
            $alarms->where('event_date', '<=', $endDate->add(new \DateInterval('P1D'))->format('Y-m-d H:i:s'));
        }

        return ($paginate) ? $alarms->paginate(10) : $alarms->get();
    }

    public static function getByID($alarmID)
    {
        return Alarm::find($alarmID);
    }

    /**
     * @param $facilityID
     * @param $eventDate
     * @param $title
     * @param $description
     * @return Alarm
     */
    public static function createAlarm($facilityID, $eventDate, $title, $description)
    {
        $alarm = new Alarm();
        $alarm->id = Uuid::uuid4()->toString();
        $alarm->facility_id = $facilityID;
        $alarm->event_date = $eventDate;
        $alarm->title = $title;
        $alarm->description = $description;

        $alarm->save();

        return $alarm->id;
    }

    /**
     * @param $alarmID
     * @param $facilityID
     * @param $eventDate
     * @param $title
     * @param $description
     * @return bool
     */
    public static function udpateAlarm($alarmID, $facilityID, $eventDate, $title, $description)
    {
        if ($alarm = Alarm::find($alarmID)) {

            $alarm->facility_id = $facilityID;
            $alarm->event_date = $eventDate;
            $alarm->title = $title;
            $alarm->description = $description;
            $alarm->save();

            return true;
        }

        return false;
    }

    /**
     * @param $alarmID
     * @return bool
     */
    public static function deleteAlarm($alarmID)
    {
        if ($alarm = Alarm::find($alarmID)) {
            $alarm->delete();

            return true;
        }

        return false;
    }
}
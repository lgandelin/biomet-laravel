<?php

namespace Webaccess\BiometLaravel\Services;

use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Alarm;

class AlarmManager
{
    public static function getAllByFacilityID($facilityID, $startDate = null, $endDate = null, $paginate = true)
    {
        $alarms = Alarm::where('facility_id', '=', $facilityID)->orderBy('created_at');

        if ($startDate) {
            $alarms->where('event_date', '>=', $startDate);
        }

        if ($endDate) {
            $endDate = (new \DateTime($endDate))->add(new \DateInterval('P1D'))->format('Y-m-d H:i:s');
            $alarms->where('event_date', '<=', $endDate);
        }

        return ($paginate) ? $alarms->paginate(10) : $alarms->get();
    }

    public static function getByID($alarmID)
    {
        return Alarm::find($alarmID);
    }

    /**
     * @param $name
     * @param $accessLimitDate
     * @return Alarm
     */
    public static function createAlarm($name, $accessLimitDate = null)
    {
        $alarm = new Alarm();
        $alarm->id = Uuid::uuid4()->toString();

        $alarm->save();

        return $alarm->id;
    }

    /**
     * @param $alarmID
     * @param $name
     * @param $accessLimitDate
     * @return bool
     */
    public static function udpateAlarm($alarmID, $name, $accessLimitDate)
    {
        if ($alarm = Alarm::find($alarmID)) {
            $alarm->name = $name;
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
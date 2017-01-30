<?php

namespace Webaccess\BiometLaravel\Services;

use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Equipment;

class EquipmentManager
{
    public static function getAllByFacilityID($facilityID)
    {
        return Equipment::where('facility_id', '=', $facilityID)->orderBy('created_at', 'desc')->get();
    }

    public static function getByID($equipmentID)
    {
        return Equipment::find($equipmentID);
    }

    /**
     * @param $name
     * @param $tag
     * @param $facilityID
     * @return Equipment
     */
    public static function createEquipment($name, $tag, $facilityID)
    {
        $equipment = new Equipment();
        $equipment->id = Uuid::uuid4()->toString();
        $equipment->facility_id = $facilityID;
        $equipment->hours_functionning = 0;
        $equipment->name = $name;
        $equipment->tag = $tag;

        $equipment->save();

        return $equipment->id;
    }

    /**
     * @param $equipmentID
     * @param $name
     * @param $tag
     * @param int $hoursFunctionning
     * @return bool
     */
    public static function udpateEquipment($equipmentID, $name = '', $tag = '', $hoursFunctionning = 0)
    {
        if ($equipment = Equipment::find($equipmentID)) {
            if ($name) $equipment->name = $name;
            if ($tag) $equipment->tag = $tag;
            $equipment->hours_functionning = $hoursFunctionning;
            $equipment->save();

            return true;
        }

        return false;
    }

    /**
     * @param $equipmentID
     * @return bool
     */
    public static function deleteEquipment($equipmentID)
    {
        if ($equipment = Equipment::find($equipmentID)) {
            $equipment->delete();

            return true;
        }

        return false;
    }
}

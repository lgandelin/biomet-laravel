<?php

namespace Webaccess\BiometLaravel\Services;

use Ramsey\Uuid\Uuid;
use Webaccess\BiometLaravel\Models\Equipment;

class EquipmentManager
{
    /**
     * @param $facilityID
     * @return mixed
     */
    public static function getAllByFacilityID($facilityID)
    {
        return Equipment::where('facility_id', '=', $facilityID)->orderBy('order', 'asc')->get();
    }

    /**
     * @param $equipmentID
     * @return mixed
     */
    public static function getByID($equipmentID)
    {
        return Equipment::find($equipmentID);
    }

    /**
     * @param $facilityID
     * @param $equipmentTag
     * @return mixed
     */
    public static function getByFacilityIDAndTag($facilityID, $equipmentTag)
    {
        return Equipment::where('facility_id', '=', $facilityID)->where('tag', 'LIKE', '%' . $equipmentTag . '%')->first();
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
        $equipment->partial_counter = 0;
        $equipment->total_counter = 0;
        $equipment->name = $name;
        $equipment->tag = $tag;

        $equipment->save();

        return $equipment->id;
    }

    /**
     * @param $equipmentID
     * @param string $name
     * @param string $tag
     * @param int $partialCounter
     * @param int $totalCounter
     * @return bool
     */
    public static function udpateEquipment($equipmentID, $name = '', $tag = '', $partialCounter = 0, $totalCounter = 0)
    {
        if ($equipment = Equipment::find($equipmentID)) {
            if ($name) $equipment->name = $name;
            if ($tag) $equipment->tag = $tag;
            $equipment->partial_counter = $partialCounter;
            $equipment->total_counter = $totalCounter;
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

<?php

namespace Webaccess\BiometLaravel\Services;

use DateTime;
use DirectoryIterator;
use IteratorIterator;
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

    /**
     * @param $interventionID
     * @param $fileName
     * @return bool
     */
    public static function deleteInterventionFile($interventionID, $fileName)
    {
        if ($intervention = Intervention::find($interventionID)) {
            $interventionFolder = env('DATA_FOLDER_PATH') . '/interventions/' . $intervention->facility_id . '/' . $interventionID . '/';

            if (file_exists($interventionFolder . $fileName)) {
                unlink($interventionFolder . $fileName);

                return true;
            }
        }

        return false;
    }

    /**
     * @param $interventionID
     * @return bool
     */
    public static function getFilesByID($interventionID)
    {
        $entries = [];
        if ($intervention = Intervention::find($interventionID)) {

            $interventionFolder = env('DATA_FOLDER_PATH') . '/interventions/' . $intervention->facility_id . '/' . $interventionID . '/';

            if (is_dir($interventionFolder)) {
                $path = realpath($interventionFolder);

                foreach (new IteratorIterator(new DirectoryIterator($path)) as $entry) {

                    $fileName = preg_replace('#' . $path . '/#', '', $entry->getPathname());

                    if (!preg_match('/^\./', $fileName)) {
                        //Files
                        $entries[] = [
                            'name' => $fileName,
                            'creation_date' => (new DateTime())->setTimestamp(filemtime($entry->getPathname()))->format('d/m/Y H:i:s'),
                            'size' => self::getReadableFilesize(filesize($entry->getPathname())),
                            //'link' => route('facility_download_file', [])
                        ];
                    }
                }
            }
        }

        return $entries;
    }

    /**
     * @param $bytes
     * @param int $decimals
     * @return string
     */
    public static function getReadableFilesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
}
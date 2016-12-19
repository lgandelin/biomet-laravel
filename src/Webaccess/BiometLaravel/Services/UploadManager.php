<?php

namespace Webaccess\BiometLaravel\Services;

use Illuminate\Support\Str;

class UploadManager
{
    public static function uploadInterventionFiles($files, $facilityID, $interventionID)
    {
        $facilityFolder = env('DATA_FOLDER_PATH') . '/interventions/' . $facilityID;
        $interventionFolder = $facilityFolder  . '/' . $interventionID;

        self::createFolderIfNotExists($facilityFolder);
        self::createFolderIfNotExists($interventionFolder);

        $mimeType = null;
        $uploadedFile = null;
        $thumbnailName = null;
        foreach ($files as $file) {
            $file->getMimeType();
            $file->move($interventionFolder, self::slugify($file->getClientOriginalName()));
        }
    }

    private static function createFolderIfNotExists($uploadsFolder)
    {
        if (!is_dir($uploadsFolder)) {
            mkdir($uploadsFolder);
        }
    }

    public static function extractExtension($string)
    {
        return pathinfo($string, PATHINFO_EXTENSION);
    }

    public static function removeExtension($string, $extension)
    {
        return preg_replace('/'.$extension.'/', '', $string);
    }

    private static function slugify($string)
    {
        $extension = self::extractExtension($string);
        $string = ($extension) ? self::removeExtension($string, $extension) : $string;
        $string = Str::slug($string);
        $string = ($extension) ? $string.'.'.$extension : $string;

        return $string;
    }
}

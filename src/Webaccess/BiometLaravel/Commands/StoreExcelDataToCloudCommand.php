<?php

namespace Webaccess\BiometLaravel\Commands;

use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Webaccess\BiometLaravel\Services\FacilityManager;
use Webaccess\BiometLaravel\Services\OVHObjectStorage;

class StoreExcelDataToCloudCommand extends Command
{
    protected $signature = 'biomet:store-excel-data-to-cloud {date} {facility_id?}';

    protected $description = 'Stocke les fichiers de données brutes au format Excel sur le cloud d\'OVH';

    public function handle()
    {
        date_default_timezone_set('Europe/Paris');
        ini_set('memory_limit', -1);

        $facilities = FacilityManager::getAll(false);
        if ($this->argument('facility_id')) {
            $facilities = array(FacilityManager::getByID($this->argument('facility_id')));
        }

        foreach ($facilities as $facility) {
            $folder = env('DATA_FOLDER_PATH') . '/xls/' . $facility->id;

            $yesterdayDate = DateTime::createFromFormat('Y-m-d', $this->argument('date'))->setTime(0, 0, 0)->sub(new DateInterval('P1D'));
            $dayFolder = $folder . '/' . $yesterdayDate->format('Y/m/d');

            $containerName = $facility->id;

            //Updload data file
            $dataFile = 'data-' . $yesterdayDate->format('Y-m-d') . '.xlsx';
            $fileName = $dayFolder . '/' . $dataFile;
            $fileNameInContainer = $dataFile;

            if (file_exists($fileName)) {
                (new OVHObjectStorage())->uploadFileToContainer($containerName, $fileName, $fileNameInContainer);

                $this->info('Fichier de données archivé sur le cloud avec succès pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate->format('d/m/Y'));
            } else {
                $this->error('Data file not existing : ' . $fileName);
            }

            //Upload client file
            $dataFile = 'data_client-' . $yesterdayDate->format('Y-m-d') . '.xlsx';
            $fileName = $dayFolder . '/' . $dataFile;
            $fileNameInContainer = $dataFile;

            if (file_exists($fileName)) {
                (new OVHObjectStorage())->uploadFileToContainer($containerName, $fileName, $fileNameInContainer);

                $this->info('Fichier client archivé sur le cloud avec succès pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate->format('d/m/Y'));
            } else {
                $this->error('Client file not existing : ' . $fileName);
            }
        }
    }
}

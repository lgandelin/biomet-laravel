<?php

namespace Webaccess\BiometLaravel\Commands;

use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Webaccess\BiometLaravel\Services\FacilityManager;

class PurgeDataFilesCommand extends Command
{
    protected $signature = 'biomet:purge-data-files {facility_id?}';

    protected $description = 'Purge les anciens fichiers de données bruts Excel (plus de 7 jours)';

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

            $i = 0;
            $files = glob($folder . '/*.xlsx');
            foreach ($files as $file) {
                $data = preg_match('/(.*)([0-9]{8})/', $file, $matches);
                $date = $matches[2];

                if (DateTime::createFromFormat('Ymd', $date) < (new DateTime())->sub(new DateInterval('P7D'))) {
                    $i++;
                    unlink($file);
                }
            }

            $this->info('Purge des fichiers de données effectuée pour le site ' . $facility->id . ' (' . $i . ' fichiers supprimés)');
        }
    }
}

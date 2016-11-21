<?php

namespace Webaccess\BiometLaravel\Commands;

use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Webaccess\BiometLaravel\Services\FacilityManager;

class GenerateSampleExcelDataCommand extends Command
{
    protected $signature = 'biomet-dev:generate-sample-excel-data {date} {start_date?}';

    protected $description = 'Insère des fichiers XLS d\'exemple pour les sites';

    public function handle()
    {
        foreach (FacilityManager::getAll(false) as $facility) {

            $endDate = DateTime::createFromFormat('Y-m-d', $this->argument('date'));
            $endDate->setTime(0, 0, 0);

            if ($this->argument('start_date')) {
                $startDate = DateTime::createFromFormat('Y-m-d', $this->argument('start_date'));
                $startDate->setTime(0, 0, 0);
            } else {
                $date = clone $endDate;
                $startDate = $date->sub(new DateInterval('P1D'));
            }

            while($startDate < $endDate) {
                $date = clone $startDate;

                $folder = env('DATA_FOLDER_PATH') . '/xls/' . $facility->id;
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }

                $folder = env('DATA_FOLDER_PATH') . '/xls/' . $facility->id . '/' . $date->format('Y/m/d');
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }

                $nextDay = clone $date;
                $nextDay->add(new DateInterval('P1D'));

                copy(env('DATA_FOLDER_PATH') . '/xls/' . 'EXPORT_BIOMET.xlsx', $folder . '/data.xlsx');
                $startDate->add(new DateInterval('P1D'));
            }
        }

        $this->info('Fichiers XLS générés avec succès');
    }
}

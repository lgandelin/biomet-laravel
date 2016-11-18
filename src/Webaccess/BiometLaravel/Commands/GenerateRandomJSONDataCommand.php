<?php

namespace Webaccess\BiometLaravel\Commands;

use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Webaccess\BiometLaravel\Services\FacilityManager;

class GenerateRandomJSONDataCommand extends Command
{
    protected $signature = 'biomet:generate-random-json-data {date} {start_date?}';

    protected $description = 'Insère des fichiers JSON d\'exemple pour les sites';

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

                $folder = env('DATA_FOLDER_PATH') . '/sites/' . $facility->id;
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }

                $folder = env('DATA_FOLDER_PATH') . '/sites/' . $facility->id . '/' . $date->format('Y/m/d');
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }

                $jsonFile = $folder . '/data.json';

                $data = [];

                $nextDay = clone $date;
                $nextDay->add(new DateInterval('P1D'));

                while ($date < $nextDay) {
                    $data[]= [
                        'timestamp' => $date->getTimestamp(),
                        'FT0101F' => mt_rand(0, 1000),
                        'FT0102F' => mt_rand(0, 1000),

                        'AP0101_CH4' => mt_rand(0, 1000),
                        'AP0101_CO2' => mt_rand(0, 1000),
                        'AP0101_H2O' => mt_rand(0, 1000),
                        'AP0101_H2S' => mt_rand(0, 1000),
                        'AP0101_O2' => mt_rand(0, 1000),

                        'AP0201_CH4' => mt_rand(0, 1000),
                        'AP0201_CO2' => mt_rand(0, 1000),
                        'AP0201_H2O' => mt_rand(0, 1000),
                        'AP0201_H2S' => mt_rand(0, 1000),
                        'AP0201_O2' => mt_rand(0, 1000),

                        'AP0202_CH4' => mt_rand(0, 1000),
                        'AP0202_CO2' => mt_rand(0, 1000),
                        'AP0202_H2O' => mt_rand(0, 1000),
                        'AP0202_H2S' => mt_rand(0, 1000),
                        'AP0202_O2' => mt_rand(0, 1000),

                        'AP0203_CH4' => mt_rand(0, 1000),
                        'AP0203_CO2' => mt_rand(0, 1000),
                        'AP0203_H2O' => mt_rand(0, 1000),
                        'AP0203_H2S' => mt_rand(0, 1000),
                        'AP0203_O2' => mt_rand(0, 1000),

                        'IGP' => mt_rand(0, 1000),

                        'Q_DIGEST' => mt_rand(0, 1000),
                    ];
                    $date->add(new DateInterval('PT15M'));
                }

                file_put_contents($jsonFile, utf8_encode(json_encode($data, JSON_PRETTY_PRINT)));
                $startDate->add(new DateInterval('P1D'));
            }
        }

        $this->info('Fichiers JSON générés avec succès');
    }
}

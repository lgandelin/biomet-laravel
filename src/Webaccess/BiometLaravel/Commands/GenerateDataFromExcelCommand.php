<?php

namespace Webaccess\BiometLaravel\Commands;

use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PHPExcel_IOFactory;
use Webaccess\BiometLaravel\Services\AlarmManager;
use Webaccess\BiometLaravel\Services\EquipmentManager;
use Webaccess\BiometLaravel\Services\FacilityManager;

class GenerateDataFromExcelCommand extends Command
{
    protected $signature = 'biomet:generate-data-from-excel {date}';

    protected $description = 'Génère les fichiers de données et extrait les informations à partir des fichiers Excel';

    public function handle()
    {
        date_default_timezone_set('Europe/Paris');

        foreach (FacilityManager::getAll(false) as $facility) {
            $data = [];

            $folder = env('DATA_FOLDER_PATH') . '/xls/' . $facility->id;
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $yesterdayDate = DateTime::createFromFormat('Y-m-d', $this->argument('date'))->sub(new DateInterval('P1D'))->format('Y/m/d');

            $folder = env('DATA_FOLDER_PATH') . '/xls/' . $facility->id . '/' . $yesterdayDate;
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            if (!file_exists($folder . '/data.xlsx')) {
                $errorMessage = 'Data file not existing : ' . $folder . '/data.xlsx';
                Log::error($errorMessage);
                $this->error($errorMessage);

                break;
            }

            $objPHPExcel = PHPExcel_IOFactory::load($folder . '/data.xlsx');

            //Alimentation
            $objWorksheet = $objPHPExcel->getSheet(0);
            foreach ($objWorksheet->getRowIterator() as $i => $row) {
                if ($i > 2) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);
                    $timestamp = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A' . $i)->getValue())->getTimestamp();

                    foreach ($cellIterator as $j => $cell) {
                        $data[$timestamp]['timestamp'] = $timestamp;
                        if ($j == 'D') $data[$timestamp]['FT0101F'] = $cell->getValue();
                        if ($j == 'G') $data[$timestamp]['FT0102F'] = $cell->getValue();
                    }
                }
            }

            //Analyse
            $objWorksheet = $objPHPExcel->getSheet(2);
            foreach ($objWorksheet->getRowIterator() as $i => $row) {
                if ($i > 2) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);
                    $timestamp = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A' . $i)->getValue())->getTimestamp();

                    foreach ($cellIterator as $j => $cell) {
                        if ($j == 'B') $data[$timestamp]['AP0101_CH4'] = $cell->getValue();
                        if ($j == 'C') $data[$timestamp]['AP0101_CO2'] = $cell->getValue();
                        if ($j == 'D') $data[$timestamp]['AP0101_H2O'] = $cell->getValue();
                        if ($j == 'E') $data[$timestamp]['AP0101_H2S'] = $cell->getValue();
                        if ($j == 'F') $data[$timestamp]['AP0101_O2'] = $cell->getValue();

                        if ($j == 'G') $data[$timestamp]['AP0201_CH4'] = $cell->getValue();
                        if ($j == 'H') $data[$timestamp]['AP0201_CO2'] = $cell->getValue();
                        if ($j == 'I') $data[$timestamp]['AP0201_H2O'] = $cell->getValue();
                        if ($j == 'J') $data[$timestamp]['AP0201_H2S'] = $cell->getValue();
                        if ($j == 'K') $data[$timestamp]['AP0201_O2'] = $cell->getValue();

                        if ($j == 'L') $data[$timestamp]['AP0202_CH4'] = $cell->getValue();
                        if ($j == 'M') $data[$timestamp]['AP0202_CO2'] = $cell->getValue();
                        if ($j == 'N') $data[$timestamp]['AP0202_H2O'] = $cell->getValue();
                        if ($j == 'O') $data[$timestamp]['AP0202_H2S'] = $cell->getValue();
                        if ($j == 'P') $data[$timestamp]['AP0202_O2'] = $cell->getValue();

                        if ($j == 'Q') $data[$timestamp]['AP0203_CH4'] = $cell->getValue();
                        if ($j == 'R') $data[$timestamp]['AP0203_CO2'] = $cell->getValue();
                        if ($j == 'S') $data[$timestamp]['AP0203_H2O'] = $cell->getValue();
                        if ($j == 'T') $data[$timestamp]['AP0203_H2S'] = $cell->getValue();
                        if ($j == 'U') $data[$timestamp]['AP0203_O2'] = $cell->getValue();
                    }
                }
            }

            //Calcul
            $objWorksheet = $objPHPExcel->getSheet(3);
            foreach ($objWorksheet->getRowIterator() as $i => $row) {
                if ($i > 2) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);
                    $timestamp = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A' . $i)->getValue())->getTimestamp();

                    foreach ($cellIterator as $j => $cell) {
                        if ($j == 'G') $data[$timestamp]['IGP'] = $cell->getValue();
                        if ($j == 'M') $data[$timestamp]['Q_DIGEST'] = $cell->getValue();
                    }
                }
            }

            //Consommation électrique
            $objWorksheet = $objPHPExcel->getSheet(3);
            foreach ($objWorksheet->getRowIterator() as $i => $row) {
                if ($i > 3) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);
                    $timestamp = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A' . $i)->getValue())->getTimestamp();

                    foreach ($cellIterator as $j => $cell) {
                        $data[$timestamp]['timestamp'] = $timestamp;
                        if ($j == 'B') $data[$timestamp]['CONSO_ELEC_CHAUD'] = $cell->getValue();
                        if ($j == 'C') $data[$timestamp]['CONSO_ELEC_INSTAL'] = $cell->getValue();
                        if ($j == 'D') $data[$timestamp]['CONSO_ELEC_PEC'] = $cell->getValue();
                    }
                }
            }

            //Volume
            $date = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A3')->getValue());
            $date->setTime(0, 0, 0);
            $data[$date->getTimestamp()]['timestamp'] = $date->getTimestamp();
            $data[$date->getTimestamp()]['FT0101F_VOLUME'] = $this->calculateSum($data, 'FT0101F') / 60;
            $data[$date->getTimestamp()]['FT0102F_VOLUME'] = $this->calculateSum($data, 'FT0102F') / 60;

            //JSON generation
            $data = array_values($data);
            $jsonFolder = env('DATA_FOLDER_PATH') . '/json/' . $facility->id . '/' . $yesterdayDate;
            if (!is_dir($jsonFolder)) {
                mkdir($jsonFolder, 0777, true);
            }

            $jsonFile = env('DATA_FOLDER_PATH') . '/json/' . $facility->id . '/' . $yesterdayDate . '/data.json';

            file_put_contents($jsonFile, utf8_encode(json_encode($data, JSON_PRETTY_PRINT)));

            //Consignation
            $objWorksheet = $objPHPExcel->getSheet(9);
            foreach ($objWorksheet->getRowIterator() as $i => $row) {
                if ($i > 1) {
                    if (preg_match('/alarme/i', $objWorksheet->getCell('C' . $i)->getValue())) {
                        $date = $objWorksheet->getCell('A' . $i)->getValue();
                        $time = $objWorksheet->getCell('B' . $i)->getValue();
                        $title = $objWorksheet->getCell('C' . $i)->getValue();
                        $description = $objWorksheet->getCell('D' . $i)->getValue();
                        $eventDate = DateTime::createFromFormat('Y/m/d H:i:s', $date . ' ' . $time)->format('Y-m-d H:i:s');

                        AlarmManager::createAlarm($facility->id, $eventDate, $title, $description);
                    }
                }
            }

            //Heures en fonctionnement
            //$objWorksheet = $objPHPExcel->getSheet(10);

            /*$dateStepStart = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A3')->getValue());
            $dateStepEnd = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A4')->getValue());

            $timeStepInHours = 0;
            if ($dateStepStart && $dateStepEnd) {
                $timeStepInHours = $dateStepEnd->diff($dateStepStart)->format('%H');
            }

            foreach ($objWorksheet->getRowIterator() as $i => $row) {
                if ($i > 1) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);

                    foreach ($cellIterator as $j => $cell) {
                        $tag = $objWorksheet->getCell($j . '1')->getValue();
                        if ($equipment = EquipmentManager::getByFacilityIDAndTag($facility->id, $tag)) {
                            if ($cell->getValue() != "") {
                                //$equipment->hours_functionning = $equipment->hours_functionning + $timeStepInHours;
                                $equipment->hours_functionning = $cell->getValue();
                                $equipment->save();
                            }
                        }
                    }
                }
            }*/

        }

        $this->info('Données générées avec succès pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate);
    }

    private function calculateSum($data, $key) {
        $sum = 0;
        foreach ($data as $timestamp => $intervalData) {
            if (isset($intervalData[$key]))
                $sum += $intervalData[$key];
        }

        return $sum;
    }
}

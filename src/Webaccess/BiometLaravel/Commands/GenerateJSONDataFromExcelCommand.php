<?php

namespace Webaccess\BiometLaravel\Commands;

use DateTime;
use Illuminate\Console\Command;
use PHPExcel_IOFactory;

class GenerateJSONDataFromExcelCommand extends Command
{
    protected $signature = 'biomet:generate-json-data-from-excel';

    protected $description = 'Génère les fichiers JSON à partir des fichiers Excel';

    public function handle()
    {
        date_default_timezone_set('Europe/Paris');
        $data = [];
        $objPHPExcel = PHPExcel_IOFactory::load(env('DATA_FOLDER_PATH') . '/raw/EXPORT_BIOMET.xlsx');

        //Alimentation
        $objWorksheet = $objPHPExcel->getSheet(0);
        foreach ($objWorksheet->getRowIterator() as $i => $row) {
            if ($i > 2) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE);
                $timestamp = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCellByColumnAndRow('A', $i)->getValue())->getTimestamp();

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
                $timestamp = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCellByColumnAndRow('A', $i)->getValue())->getTimestamp();

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
                $timestamp = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCellByColumnAndRow('A', $i)->getValue())->getTimestamp();

                foreach ($cellIterator as $j => $cell) {
                    if ($j == 'G') $data[$timestamp]['IGP'] = $cell->getValue();
                    if ($j == 'M') $data[$timestamp]['Q_DIGEST'] = $cell->getValue();
                }
            }
        }

        //Volume
        $date = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCellByColumnAndRow('A', 3)->getValue());
        $date->setTime(0, 0, 0);
        $data[$date->getTimestamp()]['timestamp'] = $date->getTimestamp();
        $data[$date->getTimestamp()]['FT0101F_VOLUME'] = $this->calculateSum($data, 'FT0101F');
        $data[$date->getTimestamp()]['FT0102F_VOLUME'] = $this->calculateSum($data, 'FT0102F');

        //JSON generation
        $data = array_values($data);

        $jsonFile = env('DATA_FOLDER_PATH') . '/raw/data.json';
        file_put_contents($jsonFile, utf8_encode(json_encode($data, JSON_PRETTY_PRINT)));

        $this->info('Fichiers JSON générés avec succès');
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

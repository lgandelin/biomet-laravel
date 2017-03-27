<?php

namespace Webaccess\BiometLaravel\Commands;

use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PHPExcel_Cell;
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
        ini_set('memory_limit', -1);

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
            $total_conso_elec_instal = 0;
            $count_conso_elec_instal = 0;
            $objWorksheet = $objPHPExcel->getSheet(3);
            foreach ($objWorksheet->getRowIterator() as $i => $row) {
                if ($i > 2) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);
                    $timestamp = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A' . $i)->getValue())->getTimestamp();

                    foreach ($cellIterator as $j => $cell) {
                        $data[$timestamp]['timestamp'] = $timestamp;
                        if ($j == 'B') $data[$timestamp]['CONSO_ELEC_CHAUD'] = $cell->getValue();
                        if ($j == 'C') {
                            $data[$timestamp]['CONSO_ELEC_INSTAL'] = $cell->getValue();
                            $total_conso_elec_instal += $cell->getValue();
                            $count_conso_elec_instal++;
                        }
                        if ($j == 'D') $data[$timestamp]['CONSO_ELEC_PEC'] = $cell->getValue();
                    }
                }
            }

            //Consommation électrique moyenne journalière
            $date = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A3')->getValue());
            $date->setTime(0, 0, 0);
            $data[$date->getTimestamp()]['CONSO_ELEC_INSTAL_AVG_DAILY_INDICATOR'] = ($count_conso_elec_instal > 0) ? $total_conso_elec_instal / $count_conso_elec_instal : 0;

            //Volume
            $date = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A3')->getValue());
            $date->setTime(0, 0, 0);
            $data[$date->getTimestamp()]['timestamp'] = $date->getTimestamp();
            $data[$date->getTimestamp()]['FT0101F_VOLUME'] = $this->calculateSum($data, 'FT0101F') / 60;
            $data[$date->getTimestamp()]['FT0102F_VOLUME'] = $this->calculateSum($data, 'FT0102F') / 60;

            //Quantités biométhane
            $objWorksheet = $objPHPExcel->getSheet(10);
            $lastRow = $objWorksheet->getHighestRow();
            $date = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A3')->getValue());
            $date->setTime(0, 0, 0);
            $data[$date->getTimestamp()]['timestamp'] = $date->getTimestamp();
            $data[$date->getTimestamp()]['QTE_BIOMETHANE_INJECTE'] = $objWorksheet->getCell('Y' . $lastRow)->getValue();
            $data[$date->getTimestamp()]['QTE_BIOMETHANE_NON_CONFORME'] = $objWorksheet->getCell('Z' . $lastRow)->getValue();

            //PCS biométhane
            $sumPCSBiomethaneInjecte = 0;
            $sumPCSBiomethaneNonConforme = 0;
            foreach ($objWorksheet->getRowIterator() as $i => $row) {
                if ($i > 2) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);
                    $timestamp = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A' . $i)->getValue())->getTimestamp();

                    $dbtInjecte = 0;
                    $dbtNonConforme = 0;
                    $pcs = 0;
                    foreach ($cellIterator as $j => $cell) {
                        $data[$timestamp]['timestamp'] = $timestamp;
                        if ($j == 'I') $dbtInjecte = $cell->getValue();
                        if ($j == 'J') $dbtNonConforme = $cell->getValue();
                        if ($j == 'P') $pcs = $cell->getValue();
                    }
                    $sumPCSBiomethaneInjecte += ($dbtInjecte  * $pcs);
                    $sumPCSBiomethaneNonConforme += ($dbtNonConforme  * $pcs);
                }
            }
            $date = DateTime::createFromFormat('d/m/Y H:i:s', $objWorksheet->getCell('A3')->getValue());
            $date->setTime(0, 0, 0);
            $data[$date->getTimestamp()]['timestamp'] = $date->getTimestamp();
            $data[$date->getTimestamp()]['PCS_BIOMETHANE_INJECTE'] = $sumPCSBiomethaneInjecte / 60;
            $data[$date->getTimestamp()]['PCS_BIOMETHANE_NON_CONFORME'] = $sumPCSBiomethaneNonConforme / 60;

            //Heures en fonctionnement depuis le début de l'année
            $objWorksheet = $objPHPExcel->getSheet(11);
            $lastRow = $objWorksheet->getHighestRow();

            //@TODO : ne pas prendre le compteur total d'heures, mais bien depuis le début de l'année en cours !
            $data[$date->getTimestamp()]['HEURES_EN_FONCTIONNEMENT_CURRENT_YEAR'] = $objWorksheet->getCell('C' . $lastRow)->getValue();

            //Valeurs depuis le début de l'année (tableau bord)
            $dateFirstDayOfYear = new DateTime();
            $dateFirstDayOfYear->setDate($dateFirstDayOfYear->format('Y'), 1, 1)->setTime(0, 0, 0);

            $data[$date->getTimestamp()]['SUM_FT0101F_CURRENT_YEAR'] = $this->getSumValue($facility->id, $dateFirstDayOfYear, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('FT0101F')) / 60;
            $data[$date->getTimestamp()]['SUM_FT0102F_CURRENT_YEAR'] = $this->getSumValue($facility->id, $dateFirstDayOfYear, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('FT0102F')) / 60;

            $data[$date->getTimestamp()]['SUM_CONSO_ELEC_INSTALL_CURRENT_YEAR'] = $this->getPowerConsumptionAverageValue($facility->id, $dateFirstDayOfYear, new DateTime(date('Y-m-d', strtotime( '-1 days' ))));

            $data[$date->getTimestamp()]['QTE_BIOMETHANE_INJECTE_CURRENT_YEAR'] = $this->getSumValue($facility->id, $dateFirstDayOfYear, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('QTE_BIOMETHANE_INJECTE'));
            $data[$date->getTimestamp()]['PCS_BIOMETHANE_INJECTE_CURRENT_YEAR'] = $this->getSumValue($facility->id, $dateFirstDayOfYear, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('PCS_BIOMETHANE_INJECTE'));
            $data[$date->getTimestamp()]['QTE_BIOMETHANE_NON_CONFORME_CURRENT_YEAR'] = $this->getSumValue($facility->id, $dateFirstDayOfYear, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('QTE_BIOMETHANE_NON_CONFORME'));
            $data[$date->getTimestamp()]['PCS_BIOMETHANE_NON_CONFORME_CURRENT_YEAR'] = $this->getSumValue($facility->id, $dateFirstDayOfYear, new DateTime(date('Y-m-d', strtotime( '-1 days' ))), array('PCS_BIOMETHANE_NON_CONFORME'));

            //JSON generation
            $data = array_values($data);
            $jsonFolder = env('DATA_FOLDER_PATH') . '/json/' . $facility->id . '/' . $yesterdayDate;
            if (!is_dir($jsonFolder)) {
                mkdir($jsonFolder, 0777, true);
            }

            $jsonFile = env('DATA_FOLDER_PATH') . '/json/' . $facility->id . '/' . $yesterdayDate . '/data.json';

            file_put_contents($jsonFile, utf8_encode(json_encode($data)));

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
            $objWorksheet = $objPHPExcel->getSheet(11);
            $lastRow = $objWorksheet->getHighestRow();

            foreach ($objWorksheet->getRowIterator() as $i => $row) {
                if ($i == 2) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);

                    foreach ($cellIterator as $letter => $cell) {
                        if (preg_match('/([a-zA-Z0-9\-\_]*) :(.*)/', $objWorksheet->getCell($letter . '2')->getValue(), $matches)) {
                            $tag = preg_replace('/CPT_/', '', $matches[1]);
                            if ($tag != "") {
                                if ($equipment = EquipmentManager::getByFacilityIDAndTag($facility->id, $tag)) {
                                    $value = $objWorksheet->getCell($letter . $lastRow)->getValue();
                                    $colIndex = PHPExcel_Cell::columnIndexFromString($letter);

                                    if ($colIndex % 2 == 0) {
                                        $equipment->partial_counter = $value;
                                    } else {
                                        $equipment->total_counter = $value;
                                    }
                                    $equipment->save();
                                }
                            }
                        }
                    }
                }
            }
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

    /**
     * @param $facilityID
     * @param $startDate
     * @param $endDate
     * @param $keys
     * @return float
     */
    private function getSumValue($facilityID, $startDate, $endDate, $keys)
    {
        $data = FacilityManager::getData($startDate, $endDate, $facilityID, $keys, false);
        $total = 0;
        foreach ($data as $file) {
            foreach ($file['data'] as $value) {
                $total += $value[1];
            }
        }

        return round($total, 1);
    }

    private function getPowerConsumptionAverageValue($facilityID, $startDate, $endDate)
    {
        $data = FacilityManager::getData($startDate, $endDate, $facilityID, array('CONSO_ELEC_INSTAL_AVG_DAILY_INDICATOR'), false);
        $total = array_sum($data);

        return round($total * 24);
    }

}

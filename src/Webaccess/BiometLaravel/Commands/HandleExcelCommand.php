<?php

namespace Webaccess\BiometLaravel\Commands;

use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use PHPExcel_IOFactory;
use Webaccess\BiometLaravel\Services\FacilityManager;

class HandleExcelCommand extends Command
{
    protected $signature = 'biomet:handle-excel {date}';

    protected $description = 'Récupère les fichiers Excel bruts, les regroupe et les place correctement dans l\'arborescence';

    public function handle()
    {
        date_default_timezone_set('Europe/Paris');
        ini_set('memory_limit', -1);

        foreach (FacilityManager::getAll(false) as $facility) {
            $folder = env('DATA_FOLDER_PATH') . '/xls/' . $facility->id;
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $yesterdayDate = DateTime::createFromFormat('Y-m-d', $this->argument('date'))->setTime(0, 0, 0)->sub(new DateInterval('P1D'));

            //Récupération du fichier de tendances
            $files = glob($folder . '/EXP_TENDANCE_BIOMET*.xlsx', GLOB_NOSORT);
            array_multisort(array_map('filemtime', $files), SORT_NUMERIC, SORT_DESC, $files);
            $fileTendances = isset($files[0]) ? $files[0] : null;
            foreach ($files as $file) {
                if ((new DateTime())->setTimestamp(filemtime($file))->setTime(0, 0, 0)->sub(new DateInterval('P1D')) == $yesterdayDate) {
                    $fileTendances = $file;
                }
            }

            //Récupération du fichier de consignation
            $files = glob($folder . '/EXP_CONSIGNATION_BIOMET*.xlsx', GLOB_NOSORT);
            array_multisort(array_map('filemtime', $files), SORT_NUMERIC, SORT_DESC, $files);
            $fileConsignation = isset($files[0]) ? $files[0] : null;
            foreach ($files as $file) {
                if ((new DateTime())->setTimestamp(filemtime($file))->setTime(0, 0, 0)->sub(new DateInterval('P1D')) == $yesterdayDate) {
                    $fileConsignation = $file;
                }
            }


            //Récupération du fichier de maintenance
            $files = glob($folder . '/EXP_MAINTENANCE_BIOMET*.xlsx', GLOB_NOSORT);
            array_multisort(array_map('filemtime', $files), SORT_NUMERIC, SORT_DESC, $files);
            $fileMaintenance = isset($files[0]) ? $files[0] : null;
            foreach ($files as $file) {
                if ((new DateTime())->setTimestamp(filemtime($file))->setTime(0, 0, 0)->sub(new DateInterval('P1D')) == $yesterdayDate) {
                    $fileMaintenance = $file;
                }
            }

            if ($fileTendances) {
                $objPHPExcel1 = PHPExcel_IOFactory::load($fileTendances);

                if ($fileConsignation) {
                    $objPHPExcel2 = PHPExcel_IOFactory::load($fileConsignation);

                    foreach ($objPHPExcel2->getAllSheets() as $sheet) {
                        $objPHPExcel1->addExternalSheet($sheet);
                    }
                }

                if ($fileMaintenance) {
                    $objPHPExcel3 = PHPExcel_IOFactory::load($fileMaintenance);

                    foreach ($objPHPExcel3->getAllSheets() as $sheet) {
                        $objPHPExcel1->addExternalSheet($sheet);
                    }
                }

                $dayFolder = $folder . '/' . $yesterdayDate->format('Y/m/d');
                if (!is_dir($dayFolder)) {
                    mkdir($dayFolder, 0777, true);
                }

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel1, "Excel2007");
                $objWriter->save($dayFolder . '/data.xlsx');
                $this->info('Fichiers déplacés avec succès pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate->format('d/m/Y'));
            } else {
                $this->info('Fichiers bruts manquants pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate->format('d/m/Y'));
            }
        }
    }
}

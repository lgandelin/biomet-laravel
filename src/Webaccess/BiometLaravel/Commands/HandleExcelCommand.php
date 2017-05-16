<?php

namespace Webaccess\BiometLaravel\Commands;

use DateInterval;
use DateTime;
use Illuminate\Console\Command;
use PHPExcel_Reader_Excel2007;
use PHPExcel_Writer_Excel2007;
use Webaccess\BiometLaravel\Services\FacilityManager;

class HandleExcelCommand extends Command
{
    protected $signature = 'biomet:handle-excel {date} {facility_id?}';

    protected $description = 'Récupère les fichiers Excel bruts, les regroupe et les place correctement dans l\'arborescence';

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
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $yesterdayDate = DateTime::createFromFormat('Y-m-d', $this->argument('date'))->setTime(0, 0, 0)->sub(new DateInterval('P1D'));

            //Récupération du fichier de tendances
            $files = glob($folder . '/EXP_TENDANCE_BIOMET*.xlsx', GLOB_NOSORT);
            $fileTendances = null;
            foreach ($files as $file) {
                if ((new DateTime())->setTimestamp(filemtime($file))->setTime(0, 0, 0)->sub(new DateInterval('P1D')) == $yesterdayDate) {
                    $fileTendances = $file;
                }
            }

            //Récupération du fichier de consignation
            $files = glob($folder . '/EXP_CONSIGNATION_BIOMET*.xlsx', GLOB_NOSORT);
            $fileConsignation = null;
            foreach ($files as $file) {
                if ((new DateTime())->setTimestamp(filemtime($file))->setTime(0, 0, 0)->sub(new DateInterval('P1D')) == $yesterdayDate) {
                    $fileConsignation = $file;
                }
            }

            //Récupération du fichier de maintenance
            $files = glob($folder . '/EXP_MAINTENANCE_BIOMET*.xlsx', GLOB_NOSORT);
            $fileMaintenance = null;
            foreach ($files as $file) {
                if ((new DateTime())->setTimestamp(filemtime($file))->setTime(0, 0, 0)->sub(new DateInterval('P1D')) == $yesterdayDate) {
                    $fileMaintenance = $file;
                }
            }

            $objReader = new PHPExcel_Reader_Excel2007();
            $objReader->setReadDataOnly(true);

            if ($fileTendances) {
                $objPHPExcel1 = $objReader->load($fileTendances);

                if ($fileConsignation) {
                    $objPHPExcel2 = $objReader->load($fileConsignation);

                    foreach ($objPHPExcel2->getAllSheets() as $sheet) {
                        $objPHPExcel1->addExternalSheet($sheet);
                    }

                    $objPHPExcel2->disconnectWorksheets();
                    unset($objPHPExcel2);
                }

                if ($fileMaintenance) {
                    $objPHPExcel3 = $objReader->load($fileMaintenance);

                    foreach ($objPHPExcel3->getAllSheets() as $sheet) {
                        $objPHPExcel1->addExternalSheet($sheet);
                    }

                    $objPHPExcel3->disconnectWorksheets();
                    unset($objPHPExcel3);
                }

                $dayFolder = $folder . '/' . $yesterdayDate->format('Y/m/d');
                if (!is_dir($dayFolder)) {
                    mkdir($dayFolder, 0777, true);
                }

                $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel1);
                $objWriter->save($dayFolder . '/data.xlsx');

                $this->info('Fichiers déplacés avec succès pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate->format('d/m/Y'));
            } else {
                $this->info('Fichiers bruts manquants pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate->format('d/m/Y'));
            }
        }
    }
}

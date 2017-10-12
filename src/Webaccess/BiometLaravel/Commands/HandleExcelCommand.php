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

            $todayDate = DateTime::createFromFormat('Y-m-d', $this->argument('date'))->setTime(0, 0, 0);

            //Récupération du fichier de tendances
            $files = glob($folder . '/EXP_TENDANCE_*' . $todayDate->format('Ymd') . '.xlsx', GLOB_NOSORT);
            $fileTendances = null;
            if (is_array($files) && sizeof($files) > 0) {
                $fileTendances = $files[0];
            }

            //Récupération du fichier de consignation
            $files = glob($folder . '/EXP_CONSIGNATION_*' . $todayDate->format('Ymd') . '.xlsx', GLOB_NOSORT);
            $fileConsignation = null;
            if (is_array($files) && sizeof($files) > 0) {
                $fileConsignation = $files[0];
            }

            //Récupération du fichier de maintenance
            $files = glob($folder . '/EXP_MAINTENANCE_*' . $todayDate->format('Ymd') . '.xlsx', GLOB_NOSORT);
            $fileMaintenance = null;
            if (is_array($files) && sizeof($files) > 0) {
                $fileMaintenance = $files[0];
            }

            $yesterdayDate = clone $todayDate;
            $yesterdayDate->sub(new DateInterval('P1D'));

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

                $this->info('Fichier de données déplacé avec succès pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate->format('d/m/Y'));


                //Traitement fichier client
                $files = glob($folder . '/EXP_CLIENT*' . $todayDate->format('Ymd') . '.xlsx', GLOB_NOSORT);
                if (is_array($files) && sizeof($files) > 0) {
                    if (copy($files[0], $dayFolder . '/data_client.xlsx')) {
                        $this->info('Fichier client déplacé avec succès pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate->format('d/m/Y'));
                    } else {
                        $this->error('Une erreur est survenue lors du traitement du fichier client pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate->format('d/m/Y'));
                    }
                }
            } else {
                $this->error('Fichiers bruts manquants pour le site ' . $facility->id . ' à la date du ' . $yesterdayDate->format('d/m/Y'));
            }
        }
    }
}

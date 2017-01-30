<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Webaccess\BiometLaravel\Services\EquipmentManager;

class EquipmentController extends BaseController
{
    public function reset_functionning_hours()
    {
        parent::__construct($this->request);

        try {
            EquipmentManager::udpateEquipment(
                $this->request->id,
                '',
                '',
                0
            );

            $this->request->session()->flash('confirmation', trans('biomet::equipments.reset_hours_functionning_equipment_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::equipments.reset_hours_functionning_equipment_error'));
        }

        return redirect()->route('facility_tab', ['id' => $this->request->facility_id, 'tab' => 8]);
    }
}
<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Webaccess\BiometLaravel\Services\InterventionManager;
use Webaccess\BiometLaravel\Services\UploadManager;

class InterventionController extends BaseController
{
    public function add()
    {
        parent::__construct($this->request);

        return view('biomet::pages.facility.interventions.add', [
            'facility_id' => $this->request->id,
            'files' => [],
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function store()
    {
        try {
            $interventionID = InterventionManager::createIntervention(
                $this->request->input('facility_id'),
                $this->request->input('event_date'),
                $this->request->input('title'),
                $this->request->input('personal_information'),
                $this->request->input('description')
            );

            $files = $this->request->file('files');
            if (is_array($files) && sizeof($files) > 0) {
                UploadManager::uploadInterventionFiles(
                    $files,
                    $this->request->input('facility_id'),
                    $interventionID
                );
            }

            $this->request->session()->flash('confirmation', trans('biomet::interventions.add_intervention_success'));

            return redirect()->route('facility_tab', ['id' => $this->request->input('facility_id'), 'tab' => 10]);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.add_intervention_error'));

            return redirect()->route('facility_tab', ['id' => $this->request->input('facility_id'), 'tab' => 10]);
        }
    }

    public function edit()
    {
        parent::__construct($this->request);

        try {
            $intervention = InterventionManager::getByID($this->request->id);
            $files = InterventionManager::getFilesByID($this->request->id);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.intervention_not_found_error'));

            return redirect()->route('dashboard');
        }

        return view('biomet::pages.facility.interventions.edit', [
            'intervention' => $intervention,
            'files' => $files,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            InterventionManager::udpateIntervention(
                $this->request->input('intervention_id'),
                $this->request->input('facility_id'),
                $this->request->input('event_date'),
                $this->request->input('title'),
                $this->request->input('personal_information'),
                $this->request->input('description')
            );

            $files = $this->request->file('files');
            if (is_array($files) && sizeof($files) > 0) {
                UploadManager::uploadInterventionFiles(
                    $files,
                    $this->request->input('facility_id'),
                    $this->request->input('intervention_id')
                );
            }

            $this->request->session()->flash('confirmation', trans('biomet::interventions.edit_intervention_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.edit_intervention_error'));
        }

        return redirect()->route('interventions_edit', ['id' => $this->request->input('intervention_id')]);
    }

    public function delete()
    {
        try {
            $interventionID = $this->request->id;
            if ($intervention = InterventionManager::getByID($interventionID)) {
                InterventionManager::deleteIntervention($interventionID);
                $this->request->session()->flash('confirmation', trans('biomet::interventions.delete_intervention_success'));
            } else {
                $this->request->session()->flash('error', trans('biomet::interventions.delete_intervention_error'));
            }
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.delete_intervention_error'));
        }

        return redirect()->route('facility_tab', ['id' => $intervention->facility_id, 'tab' => 10]);
    }

    public function download_file()
    {
        parent::__construct($this->request);

        $interventionID = $this->request->id;
        if ($intervention = InterventionManager::getByID($interventionID)) {
            $interventionFolder = env('DATA_FOLDER_PATH') . '/interventions/' . $intervention->facility_id . '/' . $interventionID . '/';

            if (file_exists($interventionFolder . $this->request->file_name)) {
                return response()->download($interventionFolder . $this->request->file_name);
            }
        }

        return redirect()->route('interventions_edit', ['id' => $this->request->id]);
    }

    public function delete_file()
    {
        parent::__construct($this->request);

        try {
            if ($intervention = InterventionManager::getByID($this->request->id)) {
                InterventionManager::deleteInterventionFile(
                    $this->request->id,
                    $this->request->file_name
                );
                $this->request->session()->flash('confirmation', trans('biomet::interventions.delete_file_success'));
            }
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.delete_file__error'));
        }

        return redirect()->route('interventions_edit', ['id' => $this->request->id]);
    }
}
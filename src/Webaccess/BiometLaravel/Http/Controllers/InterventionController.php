<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Webaccess\BiometLaravel\Services\InterventionManager;

class InterventionController extends BaseController
{
    public function add()
    {
        return view('biomet::pages.facility.interventions.add', [
            'facility_id' => $this->request->id,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function store()
    {
        try {
            InterventionManager::createIntervention(
                $this->request->input('facility_id'),
                $this->request->input('event_date'),
                $this->request->input('title'),
                $this->request->input('personal_information'),
                $this->request->input('description')
            );
            $this->request->session()->flash('confirmation', trans('biomet::interventions.add_intervention_success'));

            return redirect()->route('facility_tab', ['id' => $this->request->input('facility_id'), 'tab' => 10]);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.add_intervention_error'));

            return redirect()->route('facility_tab', ['id' => $this->request->input('facility_id'), 'tab' => 10]);
        }
    }

    public function edit()
    {
        try {
            $intervention = InterventionManager::getByID($this->request->id);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.intervention_not_found_error'));

            return redirect()->route('facility_tab', ['id' => $this->request->input('facility_id'), 'tab' => 10]);
        }

        return view('biomet::pages.facility.interventions.edit', [
            'intervention' => $intervention,
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
            $this->request->session()->flash('confirmation', trans('biomet::interventions.edit_intervention_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::interventions.update_intervention_error'));
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
}
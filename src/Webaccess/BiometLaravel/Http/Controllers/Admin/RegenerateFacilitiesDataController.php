<?php

namespace Webaccess\BiometLaravel\Http\Controllers\Admin;

use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Artisan;
use Webaccess\BiometLaravel\Http\Controllers\BaseController;

class RegenerateFacilitiesDataController extends BaseController
{
    public function index()
    {
        parent::__construct($this->request);

        return view('biomet::pages.regenerate_data.index', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function regenerate_data_handler()
    {
        set_time_limit(0);
        parent::__construct($this->request);

        try {
            $startDate = DateTime::createFromFormat('d/m/Y', $this->request->input('start_date'));
            $endDate = DateTime::createFromFormat('d/m/Y', $this->request->input('end_date'));
            $date = clone $startDate;

            while ($date <= $endDate) {
                $date->add(new DateInterval('P1D'));

                Artisan::call('biomet:handle-excel', [
                    'date' => $date->format('Y-m-d')
                ]);

                Artisan::call('biomet:generate-data-from-excel', [
                    'date' => $date->format('Y-m-d')
                ]);
            }

            $this->request->session()->flash('confirmation', trans('biomet::facilities.regenerate_data_facility_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('biomet::facilities.regenerate_data_facility_error'));
        }

        return redirect()->route('regenerate_data');
    }
}
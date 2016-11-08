<?php

namespace Webaccess\BiometLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webaccess\BiometLaravel\Services\ClientManager;
use Webaccess\BiometLaravel\Services\FacilityManager;

class FacilityController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('biomet::pages.facilities.index', [
            'facilities' => FacilityManager::getAll(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function view(Request $request)
    {
        return view('biomet::pages.facility.index', [
            'facility' => FacilityManager::getFacility($request->id),
        ]);
    }

    public function add(Request $request)
    {
        return view('biomet::pages.facilities.add', [
            'clients' => ClientManager::getAll(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function store(Request $request)
    {
        try {
            FacilityManager::createFacility(
                $request->input('name'),
                $request->input('longitude'),
                $request->input('latitude'),
                $request->input('address'),
                $request->input('city'),
                $request->input('department'),
                $request->input('client_id')
            );
            $request->session()->flash('confirmation', trans('biomet::facilities.add_facility_success'));

            return redirect()->route('facilities');
        } catch (\Exception $e) {
            dd($e->getMessage());
            $request->session()->flash('error', trans('biomet::facilities.add_facility_error'));

            return redirect()->route('facilities_add');
        }
    }

    public function edit(Request $request)
    {
        try {
            $facility = FacilityManager::getFacility($request->id);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('biomet::facilities.facility_not_found_error'));

            return redirect()->route('facilities');
        }

        return view('biomet::pages.facilities.edit', [
            'facility' => $facility,
            'clients' => ClientManager::getAll(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update(Request $request)
    {
        try {
            FacilityManager::udpateFacility(
                $request->input('facility_id'),
                $request->input('name'),
                $request->input('longitude'),
                $request->input('latitude'),
                $request->input('address'),
                $request->input('city'),
                $request->input('department'),
                $request->input('client_id')
            );
            $request->session()->flash('confirmation', trans('biomet::facilities.edit_facility_success'));
        } catch (\Exception $e) {
            dd($e->getMessage());
            $request->session()->flash('error', trans('biomet::facilities.update_facility_error'));
        }

        return redirect()->route('facilities_edit', ['id' => $request->input('facility_id')]);
    }

    public function delete(Request $request)
    {
        try {
            FacilityManager::deleteFacility($request->id);
            $request->session()->flash('confirmation', trans('biomet::facilities.delete_facility_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('biomet::facilities.delete_facility_error'));
        }

        return redirect()->route('facilities');
    }
}
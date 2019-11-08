<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCollection;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Service::class);
        
        return new ServiceCollection(
            Service::filterOn($request)->paginate($request->itemsPerPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Service::class);

        $this->validate($request, [
            //
        ]);

        return Service::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);

        $this->validate($request, [
            //
        ]);

        return Service::updateRecord($request, $service);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);

        return Service::destroyRecord($service);
    }

    /**
     * Remove the bulkdelete resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function bulkdelete(Request $request)
    {
        $this->authorize('bulkDelete', Service::class);

        return Service::bulkDelete($request);
    }

    /**
     * Display a listing for combo
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function combo(Request $request)
    {
        return Service::fetchCombo($request);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Service $service
     * @return void
     */
    public function drafted(Request $request, Service $service)
    {
        $this->authorize('drafted', $service);

        $this->validate($request, [
            //
        ]);

        return Service::updateDrafted($request, $service);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Service $service
     * @return void
     */
    public function dispositioned(Request $request, Service $service)
    {
        $this->authorize('dispositioned', $service);

        $this->validate($request, [
            //
        ]);

        return Service::updateDispositioned($request, $service);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Service $service
     * @return void
     */
    public function submissioned(Request $request, Service $service)
    {
        $this->authorize('submissioned', $service);

        $this->validate($request, [
            'garage' => 'required'
        ]);

        return Service::updateSubmissioned($request, $service);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Service $service
     * @return void
     */
    public function examined(Request $request, Service $service)
    {
        $this->authorize('examined', $service);

        $this->validate($request, [
            //
        ]);

        return Service::updateExamined($request, $service);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Service $service
     * @return void
     */
    public function approved(Request $request, Service $service)
    {
        $this->authorize('approved', $service);

        $this->validate($request, [
            //
        ]);

        return Service::updateApproved($request, $service);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Service $service
     * @return void
     */
    public function verify(Request $request, Service $service)
    {
        return response()->json([
            'nomor' => '024/' . $service->id . '.BR-UM.SETDA/' . $service->year,
            'no_polisi' => $service->police_id,
            'pemegang' => optional($service->police)->name,
            'nama_dinas' => optional($service->agency)->name,
            'nama_bengkel' => optional($service->garage)->name,
            'periode' => $service->periode,
        ], 200);
    }
}

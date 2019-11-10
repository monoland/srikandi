<?php

namespace App\Http\Controllers\Apps;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;

class DashboardController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function vehicleType(Request $request)
    {
        return response()->json([
            'motor' => Vehicle::filterUser($request->user())->filterKind('motor')->count(),
            'mobil' => Vehicle::filterUser($request->user())->filterKind('mobil')->count()
        ], 200);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function vehicleService(Request $request)
    {
        return response()->json([
            'motor' => Service::filterUser($request->user())->filterKind('motor')->count(),
            'mobil' => Service::filterUser($request->user())->filterKind('mobil')->count()
        ], 200);
    }
}

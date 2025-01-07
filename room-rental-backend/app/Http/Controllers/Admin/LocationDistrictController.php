<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\LocationDistrictService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationDistrictController extends Controller
{
    public function __construct(private readonly LocationDistrictService $locationDistrictService)
    {
    }

    public function index(Request $request)
    {
        $relations = $request->input('depth') == 2 ? ['wards'] : [];
        $cities = $this->locationDistrictService->findAll(['*'], $relations);
        return $this->respond($cities);
    }
    public function getWard(Request $request)
    {

        $id = $request->input('id');


        if (!$id) {
            $wards = DB::table('location_wards')->get();
            return $this->respond($wards);
        }


        $districts = DB::table('location_wards')->where('district_id', $id)->get();


        return response()->json([
            'status' => 'success',
            'data' => $districts,
        ]);
    }
}

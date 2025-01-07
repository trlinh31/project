<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\LocationCityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationCityController extends Controller
{
    public function __construct(private readonly LocationCityService $locationCityService)
    {
    }

    public function index(Request $request)
    {
        $relations = $request->input('depth') == 2 ? ['districts'] : [];
        $cities = $this->locationCityService->findAll(['*'], $relations);
        return $this->respond($cities);
    }
    public function getDistrict(Request $request)
{

    $id = $request->input('id');


    if (!$id) {
        $relations = $request->input('depth') == 2 ? ['districts'] : [];
        $cities = $this->locationCityService->findAll(['*'], $relations);
        return $this->respond($cities);
    }


    $districts = DB::table('location_districts')->where('city_id', $id)->get();

   
    return response()->json([
        'status' => 'success',
        'data' => $districts,
    ]);
}

}

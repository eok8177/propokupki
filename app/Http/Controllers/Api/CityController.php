<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\City;
use App\CityTranslate;

class CityController extends Controller
{
  public function index(Request $request)
  {
    $ip = $request->ip();
    //ToDo add geoip service

    $res = [
        'ip' => $ip,
        'id' => 314,
        'name' => 'Киев'
    ];

    return response()->json($res, 200);
  }

  public function search($city)
  {
    $cities = CityTranslate::orderBy('id', 'desc')
        ->where('title', 'LIKE', '%'.$city.'%')
        ->pluck('title', 'city_id')
        ->take(10);

    return response()->json($cities, 200);
  }
}
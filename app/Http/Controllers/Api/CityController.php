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
        'name' => 'Киев',
        'name2' => 'Києва'
    ];

    return response()->json($res, 200);
  }

  public function search($city)
  {
      $app_locale = env('APP_LOCALE', 'ua');

    $cities = CityTranslate::orderBy('id', 'desc')
        ->where('title', 'LIKE', '%'.$city.'%')
        ->where('locale', $app_locale)
        ->take(10);

    $data = array();

    foreach ($cities as $city){
        $data = [
            'id' => $city->city_id,
            'name' => $city->title,
            'name2' => $city->title2
        ];
    }

    return response()->json($data, 200);
  }
}
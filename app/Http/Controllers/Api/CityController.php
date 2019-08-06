<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\City;
use App\CityTranslate;

class CityController extends Controller
{
  public function index(Request $request)
  {
      $app_locale = env('APP_LOCALE', 'ua');
      $city = CityTranslate::where('city_id', $request->get('city_id', 314))->where('locale', $app_locale)->first();

    $res = [
        'id' => 314,
        'name' => $city->title,
        'name2' => $city->title3
    ];

    return response()->json($res, 200);
  }

  public function search($city)
  {
      $app_locale = env('APP_LOCALE', 'ua');

    $cities = CityTranslate::orderBy('title', 'asc')
        ->where('title', 'LIKE', '%'.$city.'%')
        ->where('locale', $app_locale)
        ->take(12)->get();

    $data = array();

    foreach ($cities as $city){
        $data[] = [
            'id' => $city->city_id,
            'name' => $city->title,
            'name2' => $city->title3
        ];
    }

    return response()->json($data, 200);
  }
}
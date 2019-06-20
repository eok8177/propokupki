<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Discount;
use App\Product;
use App\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
//  public function index($city_id, $test)
  public function index(Request $request)
  {

//      $city_id = $city_id ? $city_id : 314;
      $city_id = 314;
      $app_locale = env('APP_LOCALE', 'ua');

      $shops = Shop::whereHas('cities', function($q) use($city_id){
          $q->where('city_id', $city_id);
      })->where('status', 1)->get();
//      dd($shops->get());
      $data = array();
      foreach ($shops as $shop){
          $shop_id = $shop->id;
          $data[] = array(
              'id' => $shop->id,
              'title' => $shop->translate($app_locale)->first()['title'],
              'image' => asset('/storage/'.$shop->image),
              'shops' => count(Address::whereHas('shops', function($q) use($shop_id){
                  $q->where('shop_id', $shop_id);
              })->where('city_id', $city_id)->get()),
              'actions' => count(Discount::whereHas('shops', function($q) use($shop_id){
                  $q->where('shop_id', $shop_id);
              })->where('status', 1)->get()),
              'discount' => 123,
          );

      }
//    dd($data);
//    $res = [
//      0 => [
//        'id' => 0,
//        'title' => 'shop 1',
//        'image' => 'images/shop-1.jpg',
//        'shops' => '248',
//        'actions' => '17',
//        'discount' => '-50%'
//      ],
//      1 => [
//        'id' => 1,
//        'title' => 'shop 2',
//        'image' => 'images/shop-2.jpg',
//        'shops' => '143',
//        'actions' => '12',
//        'discount' => '-40%'
//      ],
//      2 => [
//        'id' => 2,
//        'title' => 'shop 3',
//        'image' => 'images/shop-3.jpg',
//        'shops' => '45',
//        'actions' => '9',
//        'discount' => '-80%'
//      ],
//      3 => [
//        'id' => 3,
//        'title' => 'shop 4',
//        'image' => 'images/shop-4.jpg',
//        'shops' => '124',
//        'actions' => '44',
//        'discount' => '-50%'
//      ],
//      4 => [
//        'id' => 4,
//        'title' => 'shop 5',
//        'image' => 'images/shop-1.jpg',
//        'shops' => '45',
//        'actions' => '9',
//        'discount' => '-20%'
//      ],
//      5 => [
//        'id' => 5,
//        'title' => 'shop 6',
//        'image' => 'images/shop-1.jpg',
//        'shops' => '248',
//        'actions' => '17',
//        'discount' => '-50%'
//      ],
//      6 => [
//        'id' => 6,
//        'title' => 'shop 7',
//        'image' => 'images/shop-2.jpg',
//        'shops' => '143',
//        'actions' => '12',
//        'discount' => '-40%'
//      ],
//      7 => [
//        'id' => 7,
//        'title' => 'shop 8',
//        'image' => 'images/shop-3.jpg',
//        'shops' => '45',
//        'actions' => '9',
//        'discount' => '-80%'
//      ],
//      8 => [
//        'id' => 8,
//        'title' => 'shop 9',
//        'image' => 'images/shop-1.jpg',
//        'shops' => '45',
//        'actions' => '9',
//        'discount' => '-20%'
//      ],
//    ];

    return response()->json($data, 200);
  }

  public function search(Request $request)
  {
    $res = [
      // 'data' => $request->input('data'),
      0 => [
        'id' => 0,
        'title' => 'shop 1',
        'image' => 'images/shop-1.jpg',
        'shops' => '248',
        'actions' => '17',
        'discount' => '-50%'
      ],
      1 => [
        'id' => 1,
        'title' => 'shop 2',
        'image' => 'images/shop-2.jpg',
        'shops' => '143',
        'actions' => '12',
        'discount' => '-40%'
      ],
      2 => [
        'id' => 2,
        'title' => 'shop 3',
        'image' => 'images/shop-3.jpg',
        'shops' => '45',
        'actions' => '9',
        'discount' => '-80%'
      ],
      3 => [
        'id' => 3,
        'title' => 'shop 4',
        'image' => 'images/shop-4.jpg',
        'shops' => '124',
        'actions' => '44',
        'discount' => '-50%'
      ],
      4 => [
        'id' => 4,
        'title' => 'shop 5',
        'image' => 'images/shop-1.jpg',
        'shops' => '45',
        'actions' => '9',
        'discount' => '-20%'
      ],
    ];

    return response()->json($res, 200);
  }

}

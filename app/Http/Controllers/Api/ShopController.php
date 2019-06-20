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

  public function index(Request $request)
  {

      $city_id = $request->get('status', 314);

      $app_locale = env('APP_LOCALE', 'ua');

      $shops = Shop::whereHas('cities', function($q) use($city_id){
          $q->where('city_id', $city_id);
      })->where('status', 1)->get();

      $data = array();

      foreach ($shops as $shop){
          $shop_id = $shop->id;
          $discount_max = Product::whereHas('discounts', function($q) use($shop_id){
              $q->whereHas('shops', function($q2) use ($shop_id){
                  $q2->where('shop_id', $shop_id);
              });
          })->orderBy('discount', 'desc')->first();

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
              'discount' => $discount_max->discount,
          );

      }

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

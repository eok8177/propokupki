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

      $city_id = $request->get('city', 314);
      $count = $request->get('count', false);

      $app_locale = env('APP_LOCALE', 'ua');

      $shops = Shop::whereHas('cities', function($q) use($city_id){
          $q->where('city_id', $city_id);
      })->where('status', 1);
      if ($count) {
        $shops->take($count);
      }

      $data = array();

      foreach ($shops->get() as $shop){

          $shop_id = $shop->id;

          $discount_max = Product::whereHas('discounts', function($q) use($shop_id){
              $q->whereHas('shops', function($q2) use ($shop_id){
                  $q2->where('shop_id', $shop_id);
              });
          })->orderBy('discount', 'desc')->first();

          $data[$shop->id] = array(
              'id' => $shop->id,
              'title' => $shop->translate($app_locale)->first()['title'],
              'image' => asset('/storage/'.$shop->image),
              'shops' => count(Address::whereHas('shops', function($q) use($shop_id){
                  $q->where('shop_id', $shop_id);
              })->where('city_id', $city_id)->get()),
              'actions' => count(Discount::whereHas('shops', function($q) use($shop_id){
                  $q->where('shop_id', $shop_id);
              })->where('status', 1)->get()),
              'discount' => $discount_max['discount'],
          );

      }

    return response()->json($data, 200);
  }

  public function search(Request $request)
  {

      $app_locale = env('APP_LOCALE', 'ua');

      $results = Shop::query();

      $results->when($request->get('city', 314), function ($query, $city_id) {
          return $query->whereHas('cities', function($q) use($city_id){
              $q->where('city_id', $city_id);
          });
      });

      $results->when($request->get('search'), function ($query, $data) {
          return $query->whereHas('translations', function ($q) use ($data) {
              $q->where('title', 'LIKE', '%'.$data.'%');
          });
      });

      $shops = $results->get();

      $data = array();

      foreach ($shops as $shop){

          $shop_id = $shop->id;

          $discount_max = Product::whereHas('discounts', function($q) use($shop_id){
              $q->whereHas('shops', function($q2) use ($shop_id){
                  $q2->where('shop_id', $shop_id);
              });
          })->orderBy('discount', 'desc')->first();

          $data[$shop->id] = array(
              'id' => $shop->id,
              'title' => $shop->translate($app_locale)->first()['title'],
              'image' => asset('/storage/'.$shop->image),
              'shops' => count(Address::whereHas('shops', function($q) use($shop_id){
                  $q->where('shop_id', $shop_id);
              })->where('city_id', $request->get('city', 314))->get()),
              'actions' => count(Discount::whereHas('shops', function($q) use($shop_id){
                  $q->where('shop_id', $shop_id);
              })->where('status', 1)->get()),
              'discount' => $discount_max['discount'],
          );

      }

      return response()->json($data, 200);

  }

  public function shop(Shop $shop)
  {
    $data = [
      'title' => $shop->title,
      'image' => asset('/storage/'.$shop->image)
    ];
    return response()->json($data, 200);
  }

}

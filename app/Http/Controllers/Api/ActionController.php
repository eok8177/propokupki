<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActionController extends Controller
{
  public function index(Request $request)
  {

      $city_id = $request->get('status', 314);

      $app_locale = env('APP_LOCALE', 'ua');

      $products = Product::whereHas('discounts', function($q) use($city_id){
          $q->whereHas('shops', function($q2) use ($city_id){
              $q2->whereHas('cities', function ($q3) use ($city_id){
                  $q3->where('city_id', $city_id);
              });
          });
      })->get();

      $data = array();

      foreach ($products as $product){

          $discount_id = $product->discounts->first()->id;

          $data_shop = Shop::whereHas('discounts', function ($q) use ($discount_id){
              $q->where('discount_id', $discount_id);
          })->first();

          $shop = array(
              'image' => asset('/storage/'.$data_shop->image),
              'dates' => $product->discounts->first()->date_start.' - '.$product->discounts->first()->date_end,
              'discount' => $product->discount
          );

          $data[] = array(
              'slug' => $product->slug,
              'title' => $product->translate($app_locale)->title,
              'image' => asset('/storage/'.$product->image),
              'desc' => $product->translate($app_locale)->title,
              'tara' => $product->quantity .' '. $product->unit .' / '. $product->price/1000 .' грн за 1000 '. $product->unit,
              'price' => $product->price - $product->price * $product->discount/100,
              'oldprice' => $product->price,
              'count' => (strtotime($product->discounts->first()->date_end) - time())/86400,
              'shop' => $shop
          );

      }


    return response()->json($data, 200);
  }


  public function search(Request $request)
  {

      $city_id = $request->get('status', 314);

      $app_locale = env('APP_LOCALE', 'ua');

      $products = Product::whereHas('discounts', function($q) use($city_id){
          $q->whereHas('shops', function($q2) use ($city_id){
              $q2->whereHas('cities', function ($q3) use ($city_id){
                  $q3->where('city_id', $city_id);
              });
          });
      })->get();

      $data = array();

      foreach ($products as $product){

          $discount_id = $product->discounts->first()->id;

          $data_shop = Shop::whereHas('discounts', function ($q) use ($discount_id){
              $q->where('discount_id', $discount_id);
          })->first();

          $shop = array(
              'image' => asset('/storage/'.$data_shop->image),
              'dates' => $product->discounts->first()->date_start.' - '.$product->discounts->first()->date_end,
              'discount' => $product->discount
          );

          $data[] = array(
              'slug' => $product->slug,
              'title' => $product->translate($app_locale)->title,
              'image' => asset('/storage/'.$product->image),
              'desc' => $product->translate($app_locale)->title,
              'tara' => $product->quantity .' '. $product->unit .' / '. $product->price/1000 .' грн за 1000 '. $product->unit,
              'price' => $product->price - $product->price * $product->discount/100,
              'oldprice' => $product->price,
              'count' => (strtotime($product->discounts->first()->date_end) - time())/86400,
              'shop' => $shop
          );

      }


    $res = [
      'data' => $request->input('data'),
      'status' => true,
      'count_actions' => 10,
      'count_shops' => 4,
      'actions' => [
        0 => [
          'title' => 'Молоко 2,5% ТМ Галичина 900 г',
          'image' => 'images/action-1.jpg',
          'url' => '/actions',
        ],
        1 => [
          'title' => 'Молоко с сахаром сгущенное, 8,5% ТМ Ичня 300 г',
          'image' => 'images/action-2.jpg',
          'url' => '/actions',
        ],
        2 => [
          'title' => 'Молоко пастеризованное 3.6% ТМ Молокия Доброй ночи 900 г',
          'image' => 'images/action-3.jpg',
          'url' => '/actions',
        ],
        3 => [
          'title' => 'Молоко овсяное 1% ТМ Союзпищепром 1000 г',
          'image' => 'images/action-4.jpg',
          'url' => '/actions',
        ],
        4 => [
          'title' => 'Молоко пастеризованное 3.6% ТМ Молокия Доброй ночи 900 г',
          'image' => 'images/action-3.jpg',
          'url' => '/actions',
        ],
        5 => [
          'title' => 'Молоко овсяное 1% ТМ Союзпищепром 1000 г',
          'image' => 'images/action-4.jpg',
          'url' => '/actions',
        ],
        6 => [
          'title' => 'Молоко пастеризованное 3.6% ТМ Молокия Доброй ночи 900 г',
          'image' => 'images/action-3.jpg',
          'url' => '/actions',
        ],
        7 => [
          'title' => 'Молоко овсяное 1% ТМ Союзпищепром 1000 г',
          'image' => 'images/action-4.jpg',
          'url' => '/actions',
        ],
      ],
      'shops' => [
        0 => [
          'title' => 'Молоко 2,5% ТМ Галичина 900 г',
          'image' => 'images/shop-1.jpg',
          'url' => '/actions',
        ],
        1 => [
          'title' => 'Молоко с сахаром сгущенное, 8,5% ТМ Ичня 300 г',
          'image' => 'images/shop-2.jpg',
          'url' => '/actions',
        ],
        2 => [
          'title' => 'Молоко пастеризованное 3.6% ТМ Молокия Доброй ночи 900 г',
          'image' => 'images/shop-3.jpg',
          'url' => '/actions',
        ],
        2 => [
          'title' => 'Молоко овсяное 1% ТМ Союзпищепром 1000 г',
          'image' => 'images/shop-4.jpg',
          'url' => '/actions',
        ],
      ],
    ];

    return response()->json($res, 200);
  }

}

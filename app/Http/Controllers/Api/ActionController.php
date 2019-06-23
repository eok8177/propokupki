<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\ProductTranslate;
use App\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;

class ActionController extends Controller
{
  public function index(Request $request)
  {

      $city_id = $request->get('city', 314);
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


          $date_now = Date::now();
          $date_start = Date::parse($product->discounts->first()->date_start);
          $date_end = Date::parse($product->discounts->first()->date_end);

          $count = $date_end->diffInDays($date_now);

          $discount_id = $product->discounts->first()->id;

          $data_shop = Shop::whereHas('discounts', function ($q) use ($discount_id){
              $q->where('discount_id', $discount_id);
          })->first();

          $shop = array(
              'image' => asset('/storage/'.$data_shop->image),
              'dates' => $date_start->format('d M').' - '.$date_end->format('d M'),
              'discount' => $product->discount.' %'
          );


          $result = explode(' ', $product->translate($app_locale)->title);
          $title = $result[0];
          unset($result[0]);
          $description = implode(' ', $result);

          if ($product->unit == 'kg'){
              $unit = 'кг';
          } elseif($product->unit == 'l'){
              $unit = 'k';
          } elseif($product->unit == 'st'){
              $unit = 'шт';
          } elseif($product->unit == 'up'){
              $unit = 'уп';
          }

          $data[] = array(
              'slug' => $product->slug,
              'title' => $title,
              'image' => asset('/storage/'.$product->image),
              'desc' => $description,
              'tara' => $product->quantity .' '. $unit .' / '. round($product->price/$product->quantity, 2) .' грн за 1 '. $unit,
              'price' => round($product->price - $product->price * $product->discount/100, 2),
              'oldprice' => $product->price,
              'count' => $count,
              'shop' => $shop
          );

      }


    return response()->json($data, 200);
  }


  public function search(Request $request)
  {
      if ($request->post('data')){
          $city_id = $request->get('city', 314);
          $data = $request->get('data', 314);
          $app_locale = env('APP_LOCALE', 'ua');
          $products_search = ProductTranslate::orderBy('id', 'desc')
              ->where('title', 'LIKE', '%'.$request->post('data').'%')
              ->pluck('product_id');

          $products = Product::whereHas('products_translations', function($q) use ($data) {
             $q->where('title', 'like', '%'. $data .'%');
          });
      }


      $products = ProductTranslate::orderBy('id', 'desc')
          ->where('title', 'LIKE', '%'.$city.'%')
          ->pluck('title', 'product_id');

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

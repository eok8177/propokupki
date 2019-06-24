<?php

namespace App\Http\Controllers\Api;

use App\Discount;
use App\Product;
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
        $app_locale = env('APP_LOCALE', 'ua');

        $results = Product::query();

        $results->when($request->get('city', 314), function ($query, $city_id) {
          return $query->whereHas('discounts', function ($q) use ($city_id) {
              $q->whereHas('shops', function($q2) use ($city_id){
                  $q2->whereHas('cities', function ($q3) use ($city_id){
                      $q3->where('city_id', $city_id);
                  });
              });
          });
        });

        $results->when($request->get('data'), function ($query, $data) {
          return $query->whereHas('translations', function ($q) use ($data) {
             $q->where('title', 'LIKE', '%'.$data.'%');
          });
        });

        $products = $results->get();

        foreach ($products as $product) {

          $data_action[] = array(
              'slug' => $product->slug,
              'title' => $product->translate($app_locale)->title,
              'image' => asset('/storage/' . $product->image),
          );
        }

        $product_arr = $results->pluck('id');

        $discounts = Discount::whereHas('products', function($q) use ($product_arr){
            $q->whereIn('product_id', $product_arr);
        })->get();

dd($discounts);
        $res = [
          'data' => $request->input('data'),
          'status' => true,
          'count_actions' => 10,
          'count_shops' => 4,
          'actions' => $data_action,
          'shops' => [
            0 => [
              'image' => 'images/shop-1.jpg',
              'url' => '/actions',
            ],
            1 => [
              'image' => 'images/shop-2.jpg',
              'url' => '/actions',
            ],
            2 => [
              'image' => 'images/shop-3.jpg',
              'url' => '/actions',
            ],
            2 => [
              'image' => 'images/shop-4.jpg',
              'url' => '/actions',
            ],
          ],
        ];

        return response()->json($res, 200);
      }

}

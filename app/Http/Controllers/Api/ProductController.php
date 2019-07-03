<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Discount;
use App\Product;
use App\ProductTranslate;
use App\Shop;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;

class ProductController extends Controller
{
  public function index($slug)
  {
    $app_locale = env('APP_LOCALE', 'ua');
    $product = Product::where('slug',$slug)->first();

    $date_now = Date::now();
    $date_start = Date::parse($product->discounts->first()->date_start);
    $date_end = Date::parse($product->discounts->first()->date_end);

    $discount_id = $product->discounts->first()->id;

    $data_shop = Shop::whereHas('discounts', function ($q) use ($discount_id){
        $q->where('discount_id', $discount_id);
    })->first();

    $shop = array(
        'image' => asset('/storage/'.$data_shop->image),
        'dates' => $date_start->format('d M').' - '.$date_end->format('d M'),
        'discount' => $product->discount
    );

    $unit = '';
    if ($product->unit == 'kg'){
        $unit = 'кг';
    } elseif($product->unit == 'l'){
        $unit = 'k';
    } elseif($product->unit == 'sht'){
        $unit = 'шт';
    } elseif($product->unit == 'up'){
        $unit = 'уп';
    }

    $taraPrice = '';
    if ($product->quantity > 0) {
      $taraPrice = round($product->price/$product->quantity, 2);
    }

    $result = explode(' ', $product->translate($app_locale)->title);
    $title = $result[0];
    unset($result[0]);
    $description = implode(' ', $result);

    $res = [
      'slug' => $product->slug,
      'title' => $title,
      'image' => asset('/storage/'.$product->image),
      'desc' => $description,
      'tara' => $product->quantity .' '. $unit .' / '. $taraPrice .' грн за 1 '. $unit,
      'price' => $product->price,
      'oldprice' => $product->old_price,
      'count' => $date_end->diffInDays($date_now),
      'shop' => $shop,
    ];

    return response()->json($res, 200);
  }


  public function related(Request $request, $slug)
  {
      if($slug){

          $app_locale = env('APP_LOCALE', 'ua');

          $get_product = ProductTranslate::whereHas('product', function ($q) use ($slug) {
              $q->where('slug', $slug);
          })->first();

          $get_title = $result = explode(' ', $get_product['title']);
          $title = $get_title[0];

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

          $results->when($title, function ($query, $title) {
              return $query->whereHas('translations', function ($q) use ($title) {
                  $q->where('title', 'LIKE', '%'.$title.'%');
              });
          });

          $results->where('id', '<>', $get_product['product_id']);

          $products = $results->get();

          $data = array();

          foreach ($products as $product){


              $date_now = Date::now();
              $date_start = Date::parse($product->discounts->first()->date_start);
              $date_end = Date::parse($product->discounts->first()->date_end);

              $count = $date_end->diffInDays($date_now);

              $discount_id = $product->discounts->first()->id;

              $data_shop = Shop::whereHas('discounts', function ($q) use ($discount_id){
                  $q->where('discount_id', $discount_id)->where('status', 1);
              })->first();

              $shop = array(
                  'image' => asset('/storage/'.$data_shop->image),
                  'dates' => $date_start->format('d M').' - '.$date_end->format('d M'),
                  'discount' => $product->discount
              );


              $result = explode(' ', $product->translate($app_locale)->title);
              $title = $result[0];
              unset($result[0]);
              $description = implode(' ', $result);

              $unit = '';
              if ($product->unit == 'kg'){
                  $unit = 'кг';
              } elseif($product->unit == 'l'){
                  $unit = 'k';
              } elseif($product->unit == 'sht'){
                  $unit = 'шт';
              } elseif($product->unit == 'up'){
                  $unit = 'уп';
              }

              $taraPrice = '';
              if ($product->quantity > 0) {
                  $taraPrice = round($product->price/$product->quantity, 2);
              }

              $data[] = array(
                  'slug' => $product->slug,
                  'title' => $title,
                  'image' => asset('/storage/'.$product->image),
                  'desc' => $description,
                  'tara' => $product->quantity .' '. $unit .' / '. $taraPrice .' грн за 1 '. $unit,
                  'price' => $product->price,
                  'oldprice' => $product->old_price,
                  'count' => $count,
                  'shop' => $shop
              );

          }

          return response()->json($data, 200);
      }

  }

}

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

    if (!$product) {
      return response()->json(['Product not found'], 404);
    }
    Date::setLocale('uk');
    $date_now = Date::now();
    $date_start = Date::parse($product->discounts->first()->date_start);
    $date_end = Date::parse($product->discounts->first()->date_end);

    $discount_id = $product->discounts->first()->id;

    $data_shop = Shop::whereHas('discounts', function ($q) use ($discount_id){
        $q->where('discount_id', $discount_id);
    })->first();

    $shop = array(
        'title' => $data_shop->title,
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
      'of' => $product->of,
      'price' => $product->price,
      'oldprice' => $product->old_price,
      'count' => $date_end->diffInDays($date_now) + 1,
      'shop' => $shop,
    ];

    return response()->json($res, 200);
  }


  public function related(Request $request, $slug)
  {
      if($slug){

          $date_now = Date::now();

          $app_locale = env('APP_LOCALE', 'ua');
          $limit = env('LIMIT_PRODUCTS', '28');

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

          $results->whereHas('discounts', function ($q) use ($date_now) {
                  $q->where('date_end', '>=', $date_now);
              });

          $results->when($title, function ($query, $title) {
              return $query->whereHas('translations', function ($q) use ($title) {
                  $q->where('title', 'LIKE', '%'.$title.'%');
              });
          });

          $results->where('id', '<>', $get_product['product_id']);

          $products = $results->paginate($limit);

          $data = array();

          $data['current_page'] = $products->currentPage();
          $data['last_page'] = $products->lastPage();
          $data['per_page'] = $products->perPage();
          $data['total'] = $products->total();

          foreach ($products as $product){


              $date_now = Date::now();
              $date_start = Date::parse($product->discounts->first()->date_start);
              $date_end = Date::parse($product->discounts->first()->date_end);

              $count = $date_end->diffInDays($date_now) + 1 ;

              $discount_id = $product->discounts->first()->id;

              $data_shop = Shop::whereHas('discounts', function ($q) use ($discount_id){
                  $q->where('discount_id', $discount_id)->where('status', 1);
              })->first();

              $shop = array(
                  'image' => asset('/storage/'.$data_shop->image),
                  'dates' => $date_start->format('d M').' - '.$date_end->format('d M'),
                  'discount' => $product->discount,
                  'title' => $data_shop->title
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

              $data['data'][] = array(
                  'slug' => $product->slug,
                  'title' => $title,
                  'image' => asset('/storage/'.$product->image),
                  'desc' => $description,
                  'tara' => $product->quantity .' '. $unit .' / '. $taraPrice .' грн за 1 '. $unit,
                  'of' => $product->of,
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

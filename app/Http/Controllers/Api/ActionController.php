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

      $app_locale = env('APP_LOCALE', 'ua');

      $results = Product::query();


      if (!empty($request->get('shops', ''))){
          $shops_id = explode(',', $request->get('shops'));
          $results->whereHas('discounts', function ($q) use ($shops_id) {
              $q->whereHas('shops', function ($q2) use ($shops_id){
                  $q2->whereIn('shop_id', $shops_id);
              });
          });
      }

      $results->when($request->get('city', 314), function ($query, $city_id) {
          return $query->whereHas('discounts', function ($q) use ($city_id) {
              $q->whereHas('shops', function($q2) use ($city_id){
                  $q2->whereHas('cities', function ($q3) use ($city_id){
                      $q3->where('city_id', $city_id);
                  });
              });
          });
      });

      $dates = $request->get('dates', '');

      $date_now = Date::now();

      switch ($dates){
          case 'now':
              $results->whereHas('discounts', function ($q) use ($date_now) {
                  $q->where('date_start', '<=', $date_now)->where('date_end', '>', $date_now)->where('status', 1);
              });
              break;
          case 'feature':
              $results->whereHas('discounts', function ($q) use ($date_now) {
                  $q->where('date_start', '>', $date_now)->where('status', 1);
              });
              break;
          case 'past':
              $results->whereHas('discounts', function ($q) use ($date_now) {
                  $q->where('date_end', '<', $date_now) ->where('status', 1);
              });
              break;
          default :
              $results->whereHas('discounts', function ($q) use ($date_now) {
                  $q->where('date_end', '>=', $date_now)->where('status', 1);
              });
      }

      $results->when($request->get('data'), function ($query, $data) {
          return $query->whereHas('translations', function ($q) use ($data) {
              $q->where('title', 'LIKE', '%'.$data.'%');
          });
      });

      $sort = $request->get('sort', 'new');

      switch ($sort){
          case 'asc' :
              $results->orderBy('price', 'asc');
              break;
          case 'desc' :
              $results->orderBy('price', 'desc');
              break;
          default:
              $results->orderBy('updated_at', 'desc');

      }

      $data = array();

      $products = $results->get();

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
              $unit = 'л';
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

        $data_product = array();

        foreach ($products as $product) {

            $data_product[] = array(
              'slug' => $product->slug,
              'title' => $product->translate($app_locale)->title,
              'image' => asset('/storage/' . $product->image),
          );
        }

        $product_arr = $results->pluck('id');

        $discounts = Discount::whereHas('products', function($q) use ($product_arr){
            $q->whereIn('product_id', $product_arr);
        })->get();


        $shops_get = Shop::query();

        $shops_get->when($request->get('city', 314), function ($query, $city_id) {
            return $query->whereHas('cities', function ($q) use ($city_id){
                    $q->where('city_id', $city_id);
                });
            });


        $shops_get->when($request->get('data'), function ($query, $data) {
            return $query->whereHas('translations', function ($q) use ($data) {
                $q->where('title', 'LIKE', '%'.$data.'%');
            });
        });
//        dd($shops_get->get());
        $shops = $shops_get->where('status', 1)->get();

        $data_shops = array();
        foreach ($shops as $shop) {
            $data_shops[] = array(
                'image' => asset('/storage/' . $shop->image), // ???
                'slug' => '?shop='.$shop->id,
            );
        }


        $res = [
          'data' => $request->input('data'),
          'status' => true,
          'count_actions' => count($discounts),
          'count_shops' => count($shops),
          'actions' => $data_product,
          'shops' => $data_shops,
        ];

        return response()->json($res, 200);
      }

}

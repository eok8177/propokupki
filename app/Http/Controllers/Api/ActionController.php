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

          $unit = '';
          if ($product->unit == 'kg'){
              $unit = 'кг';
          } elseif($product->unit == 'l'){
              $unit = 'k';
          } elseif($product->unit == 'st'){
              $unit = 'шт';
          } elseif($product->unit == 'up'){
              $unit = 'уп';
          }

          $taraPrice = '';
          if ($product->quantity > 0) {
            $taraPrice = round($product->new_price/$product->quantity, 2);
          }

          $data[] = array(
              'slug' => $product->slug,
              'title' => $title,
              'image' => asset('/storage/'.$product->image),
              'desc' => $description,
              'tara' => $product->quantity .' '. $unit .' / '. $taraPrice .' грн за 1 '. $unit,
              'price' => $product->new_price,
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

        $shops =

        $data_discount = array();
        foreach ($discounts as $discount) {
            $data_discount[] = array(
                'image' => $discount->shops(), // ???
                'slug' => asset('/storage/' . $product->image),
            );
        }


        $res = [
          'data' => $request->input('data'),
          'status' => true,
          'count_actions' => count($discounts),
          'count_shops' => 4,
          'actions' => $data_product,
          'shops' => [
            0 => [
              'image' => 'http://propokupki.ari.in.ua/storage/uploads/2/mk31TX9OI3d0gx5SMDsWWgMI15rEz1UWpCt6Iz5C.png',
              'slug' => '?city=1500648&shops=0',
            ],
            1 => [
              'image' => 'http://propokupki.ari.in.ua/storage/uploads/2/mk31TX9OI3d0gx5SMDsWWgMI15rEz1UWpCt6Iz5C.png',
              'slug' => '?city=1500648&shops=1',
            ],
            2 => [
              'image' => 'http://propokupki.ari.in.ua/storage/uploads/2/mk31TX9OI3d0gx5SMDsWWgMI15rEz1UWpCt6Iz5C.png',
              'slug' => '?city=1500648&shops=2',
            ],
            3 => [
              'image' => 'http://propokupki.ari.in.ua/storage/uploads/2/mk31TX9OI3d0gx5SMDsWWgMI15rEz1UWpCt6Iz5C.png',
              'slug' => '?city=1500648&shops=3',
            ],
          ],
        ];

        return response()->json($res, 200);
      }

}

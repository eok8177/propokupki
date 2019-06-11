<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
  public function index()
  {
    $res = [
      'slug' => 'product-slug',
      'title' => 'Крупа',
      'image' => 'images/product-1.jpg',
      'desc' => 'гречневая экстра ТМ Зерновита 1000 г',
      'tara' => '1000 г / 19,79 грн за 1000 г',
      'price' => '19,79',
      'oldprice' => '32,99',
      'count' => '13',
      'shop' => [
        'image' => 'images/shop-1.jpg',
        'dates' => '14–27 марта ',
        'discount' => '-50%'
      ],
    ];

    return response()->json($res, 200);
  }


  public function related($product)
  {
    $res = [
      0 => [
        'slug' => 'product-slug',
        'title' => 'Крупа',
        'image' => 'images/product-1.jpg',
        'desc' => 'гречневая экстра ТМ Зерновита 1000 г',
        'tara' => '1000 г / 19,79 грн за 1000 г',
        'price' => '19,79',
        'oldprice' => '32,99',
        'count' => '13',
        'shop' => [
          'image' => 'images/shop-1.jpg',
          'dates' => '14–27 марта ',
          'discount' => '-50%'
        ],
      ],
      1 => [
        'slug' => 'product-slug',
        'title' => 'Ананасы',
        'image' => 'images/action-2.jpg',
        'desc' => 'кусочками в легком сиропе ТМ Варто 565 г',
        'tara' => '565 г / 43,98 грн за 1000 г',
        'price' => '21,99',
        'oldprice' => '34,99',
        'count' => '30',
        'shop' => [
          'image' => 'images/shop-2.jpg',
          'dates' => '8 марта – 7 апреля',
          'discount' => '-34%'
        ],
      ],
      2 => [
        'slug' => 'product-slug',
        'title' => 'Хлебцы',
        'image' => 'images/action-3.jpg',
        'desc' => 'многокомпонентные, ржаные с отрубями ТМ Финн Крисп 175 г',
        'tara' => '1 шт / 31,99 грн за шт',
        'price' => '31,99',
        'oldprice' => '46,99',
        'count' => '1',
        'shop' => [
          'image' => 'images/shop-3.jpg',
          'dates' => '10–16 марта ',
          'discount' => '-40%'
        ],
      ],
      3 => [
        'slug' => 'product-slug',
        'title' => 'Вино',
        'image' => 'images/action-4.jpg',
        'desc' => 'Терре Сицилиане красное/белое сухое ТМ Кастелмарко 0,75 л',
        'tara' => '0,75 л / 80,99 грн за 1 л',
        'price' => '61,99',
        'oldprice' => '89,99',
        'count' => '10',
        'shop' => [
          'image' => 'images/shop-4.jpg',
          'dates' => '14–23 марта ',
          'discount' => '-31%'
        ],
      ],

    ];

    return response()->json($res, 200);
  }

}

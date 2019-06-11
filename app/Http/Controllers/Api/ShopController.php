<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
  public function index()
  {
    $res = [
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
      5 => [
        'id' => 5,
        'title' => 'shop 6',
        'image' => 'images/shop-1.jpg',
        'shops' => '248',
        'actions' => '17',
        'discount' => '-50%'
      ],
      6 => [
        'id' => 6,
        'title' => 'shop 7',
        'image' => 'images/shop-2.jpg',
        'shops' => '143',
        'actions' => '12',
        'discount' => '-40%'
      ],
      7 => [
        'id' => 7,
        'title' => 'shop 8',
        'image' => 'images/shop-3.jpg',
        'shops' => '45',
        'actions' => '9',
        'discount' => '-80%'
      ],
      8 => [
        'id' => 8,
        'title' => 'shop 9',
        'image' => 'images/shop-1.jpg',
        'shops' => '45',
        'actions' => '9',
        'discount' => '-20%'
      ],
    ];

    return response()->json($res, 200);
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

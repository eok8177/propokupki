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
        'title' => 'shop 1',
        'image' => 'images/shop-1.jpg',
        'shops' => '248',
        'actions' => '17',
        'discount' => '-50%'
      ],
      1 => [
        'title' => 'shop 1',
        'image' => 'images/shop-2.jpg',
        'shops' => '143',
        'actions' => '12',
        'discount' => '-40%'
      ],
      2 => [
        'title' => 'shop 1',
        'image' => 'images/shop-3.jpg',
        'shops' => '45',
        'actions' => '9',
        'discount' => '-80%'
      ],
      3 => [
        'title' => 'shop 1',
        'image' => 'images/shop-4.jpg',
        'shops' => '124',
        'actions' => '44',
        'discount' => '-50%'
      ],
      4 => [
        'title' => 'shop 1',
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
      'data' => $request->input('data'),
      0 => [
        'title' => 'shop 1',
        'image' => 'images/shop-1.jpg',
        'shops' => '248',
        'actions' => '17',
        'discount' => '-50%'
      ],
      1 => [
        'title' => 'shop 1',
        'image' => 'images/shop-2.jpg',
        'shops' => '143',
        'actions' => '12',
        'discount' => '-40%'
      ],
      2 => [
        'title' => 'shop 1',
        'image' => 'images/shop-3.jpg',
        'shops' => '45',
        'actions' => '9',
        'discount' => '-80%'
      ],
      3 => [
        'title' => 'shop 1',
        'image' => 'images/shop-4.jpg',
        'shops' => '124',
        'actions' => '44',
        'discount' => '-50%'
      ],
      4 => [
        'title' => 'shop 1',
        'image' => 'images/shop-1.jpg',
        'shops' => '45',
        'actions' => '9',
        'discount' => '-20%'
      ],
    ];

    return response()->json($res, 200);
  }

}

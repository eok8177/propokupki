<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminActionController extends Controller
{
  public function index($action)
  {
    //Get from DB
    $res = [
      0 => [
        'id' => 0,
        'title' => 'Крупа',
        'image' => 'images/action-1.jpg',
        'desc' => 'гречневая экстра ТМ Зерновита 1000 г',
        'price' => '19.79',
        'discount' => '10',
        'count' => '13',
        'type' => 'кг'
      ],
      1 => [
        'id' => 1,
        'title' => 'Ананасы',
        'image' => 'images/action-2.jpg',
        'desc' => 'кусочками в легком сиропе ТМ Варто 565 г',
        'price' => '21.99',
        'discount' => '10',
        'count' => '30',
        'type' => 'кг'
      ],
      2 => [
        'id' => 2,
        'title' => 'Хлебцы',
        'image' => 'images/action-3.jpg',
        'desc' => 'многокомпонентные, ржаные с отрубями ТМ Финн Крисп 175 г',
        'price' => '31.99',
        'discount' => '10',
        'count' => '1',
        'type' => 'кг'
      ]

    ];

    return response()->json($res, 200);
  }

  /*
  * Add product to Action
  */
  public function addProduct($action, Request $request)
  {
    // Save to DB
    // $product = Product::create($request->all());
    // $image = $request->file('image')->store('uploads/'.$product->id, 'public');
    // $product->image = $image;
    // $product->save();

    //show from DB
    return $this->index($action);
  }

  /*
  * Delete product from Action
  */
  public function deleteProduct($action, $product)
  {
    // Delete from DB

    //show from DB
    return $this->index($action);
  }


}
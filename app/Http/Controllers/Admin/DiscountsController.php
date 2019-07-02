<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\Shop;
use App\ProductTranslate;
use App\Discount;
use App\Language;
use App\DiscountTranslate;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class DiscountsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = NULL;
        if ($request->get('status') == 'on') {
            $status = 1;
        } elseif($request->get('status') == 'off') {
            $status = 0;
        }

//        $status = $request->get('status',NULL);
        $shops = $request->get('shops') ? explode(',', $request->get('shops')) : [];
        $limit = $request->get('limit', 50);
        $locale = env('APP_LOCALE', 'ua');



        if (count($shops)) {
            if ($status !== NULL){
                $discounts = Discount::whereHas('shops', function ($q) use ($shops) {
                    $q->whereIn('shop_id', $shops);
                })->where('status', $status);
            } else {
                $discounts = Discount::whereHas('shops', function ($q) use ($shops) {
                    $q->whereIn('shop_id', $shops);
                });
            }
        } else {
            if ($status !== NULL){
                $discounts = Discount::where('status', $status);
            } else {
                $discounts = Discount::query();
            }
        }

        return view('backend.discounts.index', [
            'discounts'         => $discounts->orderBy('id', 'desc')->paginate($limit),
            'app_locale'        => $locale,
            'count_on'          => count(Discount::where('status', 1)->get()),
            'count_off'         => count(Discount::where('status', 0)->get()),
            'shops'             => Shop::whereIn('id', $shops)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $discount = new Discount;

        return view('backend.discounts.create', [
            'method'        => 'create',
            'discount'      => $discount->forAdmin()['discount'],
            'contents'      => $discount->forAdmin()['contents'],
            'languages'     => Language::where('status', '1')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'slug' => 'required|unique:pages|max:255',
        ]);

        $discount = Discount::create($request->all());
        $languages = Language::where('status', '1')->get();
        $discount->shops()->attach($request->shop);

        foreach ($languages as $lang) {

            $locale = $lang->locale;

            $discount_translate = new DiscountTranslate();
            $discount_translate->discount_id = $discount->id;
            $discount_translate->locale = $locale;
            $discount_translate->title = $request->$locale['title'];
            $discount_translate->save();
        }

        return redirect()
            ->route('admin.discounts.edit', $discount->id)->with('success', 'Discount add' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return view('backend.language.show', [
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discount = Discount::find($id);

        if ($discount) {
            return view('backend.discounts.edit', [
                'method'        => 'edit',
                'discount'      => $discount,
                'languages'     => Language::where('status', '1')->get()
            ]);
        }

        return redirect()->route('admin.discounts.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $discount = Discount::find($id);
        if ($discount) {
            $request->validate([
                'slug' => Rule::unique('discounts')->ignore($discount->id),
                'slug' => 'required|max:255',
//                'product.*.slug' => 'required|unique:products,slug|max:255',
                'product.*.price' => 'required',
            ]);

            $discount->fill($request->all())->save();
            $discount->shops()->sync($request->shop);

            $languages = Language::where('status', '1')->get();

            foreach ($languages as $lang) {

                $locale = $lang->locale;

                $discount_translate = DiscountTranslate::where('discount_id', $discount->id)->where('locale', $lang->locale)->first();

                if (!$discount_translate) {
                    $discount_translate = new DiscountTranslate();
                    $discount_translate->discount_id = $discount->id;
                }
                $discount_translate->locale = $locale;
                $discount_translate->title = $request->$locale['title'];
                $discount_translate->save();
            }
            $product_not_delete = [];

            if ($request->product){

                foreach ($request->product as $product){
                    $product_save = isset($product['product_id']) ? Product::find($product['product_id']) : '';

                    if ($product_save){

                        array_push($product_not_delete, $product_save->id);

                        if (!empty($product['image'])) {
                            $img = $product['image'];
                            $product['image'] = $img->store('uploads/' . $product_save->id, 'public');
                        }

                        $product_save->update($product);

                        $translate = [];

                        foreach ($languages as $lang) {
                            $locale = $lang->locale;
                            $prod_translate = ProductTranslate::where('product_id', $product_save->id);
                            $translate['locale']  = $locale;
                            $translate['title'] = $product[$locale]['title'];
                            $prod_translate->update($translate);
                        }

                    } else {

                        $prod = Product::create($product);

                        array_push($product_not_delete, $prod->id);

                        if (!empty($product['image'])) {
                            $img = $product['image'];
                            $image = $img->store('uploads/' . $prod->id, 'public');
                            $prod->image = $image;
                            $prod->save();
                        }

                        $prod->discounts()->attach($id);

                        foreach ($languages as $lang) {
                            $locale = $lang->locale;
                            $prod_translate = new ProductTranslate();
                            $prod_translate->product_id = $prod->id;
                            $prod_translate->locale = $locale;
                            $prod_translate->title = $product[$locale]['title'];
                            $prod_translate->save();
                        }
                    }

                }
            }

            if (count($product_not_delete)){
                $old_product = DB::table('discount_product')->where('discount_id', $discount->id)->whereNotIn('product_id', $product_not_delete)->pluck('product_id');
                DB::table('products')->whereIn('id', $old_product)->delete();
                DB::table('products_translations')->whereIn('product_id', $old_product)->delete();
                DB::table('discount_product')->where('discount_id', $discount->id)->whereNotIn('product_id', $product_not_delete)->delete();
            }

            return redirect()
                ->route('admin.discounts.edit', $discount->id)
                ->with('success', 'Discount update');
        }

        return redirect()->route('admin.posts.index');
    }

    public function status($id)
    {
        $discounts = Discount::find($id);
        if(!$discounts)
            return response()->json(['error'=> 'not found any page'], 400);

        $discounts->status = 1 - $discounts->status;
        $discounts->save();

        return response()->json($discounts->status, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Discount $discount)
    {
        $old_products = DB::table('discount_product')->where('discount_id', $discount->id)->pluck('product_id');
        DB::table('products')->whereIn('id', $old_products)->delete();
        DB::table('products_translations')->whereIn('product_id', $old_products)->delete();
        DB::table('discounts_translations')->where('discount_id', $discount->id)->delete();
        DB::table('discount_shop')->where('discount_id', $discount->id)->delete();
        DB::table('discount_product')->where('discount_id', $discount->id)->delete();
        DB::table('discounts')->where('id', $discount->id)->delete();

        return response()->json('success', 200);
    }

    /**
     * Validate request form.
     *
     * @param Request $request
     */
    protected function validateForm(Request $request)
    {

        $this->validate($request, [
            'title'             => 'required|max:255',
        ]);

    }

}
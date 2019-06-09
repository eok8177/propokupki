<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\ProductTranslate;
use App\Discount;
use App\Language;
use App\DiscountTranslate;
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
        $status = $request->input('status', 1);
        $search = $request->input('search', false);
        $limit = $request->input('limit', 50);
        $locale = env('APP_LOCALE', 'ua');

        $discounts = DiscountTranslate::searchDiscounts($locale, $search, $status);

        return view('backend.discounts.index', [
            'discounts'         => $discounts->paginate($limit),
            'app_locale'        => $locale,
            'count_on'          => count(Discount::where('status', 1)->get()),
            'count_off'         => count(Discount::where('status', 0)->get()),
            'status'            => $status,
            'limit'            => $limit,
            'search'            => $search,
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
        $discount = Discount::find($id)->forAdmin();

        if ($discount) {
            return view('backend.discounts.edit', [
                'method'        => 'edit',
                'discount'      => $discount['discount'],
                'contents'      => $discount['contents'],
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
//        dd($request->all());
        $discount = Discount::find($id);

        if ($discount) {
            $request->validate([
                'slug' => Rule::unique('discounts')->ignore($discount->id),
                'slug' => 'required|max:255',
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

            if ($request->product){
                $old_product = DB::table('discount_product')->where('discount_id', $discount->id)->pluck('product_id');
                DB::table('products')->whereIn('id', $old_product)->delete();
                DB::table('products_translations')->whereIn('product_id', $old_product)->delete();

                foreach ($request->product as $key => $product){

                    $prod = Product::create($product);
//                    $image = $request->file('productimage')->store('uploads/'.$product->id, 'public');
//                    $prod->image = $image;
//                    $prod->save();

                    $prod->discounts()->syns($discount->id);

                    foreach ($languages as $lang) {

                        $locale = $lang->locale;
                        $prod_translate = new ProductTranslate();
                        $prod_translate->prod_id = $prod->id;
                        $prod_translate->locale = $locale;
                        $prod_translate->title = $request->$locale['title'];
                        $prod_translate->save();
                    }
                }
            }

            return redirect()
                ->route('admin.discounts.edit', $discount->id)
                ->with('success', 'Discount update');
        }

        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Discount $discount)
    {
        $old_addresses = DB::table('address_discount')->where('discount_id', $discount->id)->pluck('address_id');
        DB::table('addresses')->whereIn('id', $old_addresses)->delete();
        DB::table('addresses_translations')->whereIn('address_id', $old_addresses)->delete();
        DB::table('city_discount')->where('discount_id', $discount->id)->delete();
        DB::table('category_discount')->where('discount_id', $discount->id)->delete();
        DB::table('address_discount')->where('discount_id', $discount->id)->delete();
        DB::table('discounts_translations')->where('discount_id', $discount->id)->delete();
        $discount->delete();
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
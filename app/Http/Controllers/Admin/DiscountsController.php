<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\Shop;
use App\ProductTranslate;
use App\Discount;
use App\Language;
use Validator;
use App\DiscountTranslate;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use ZipArchive;


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
        $request->validate([
            'slug' => 'required|unique:pages|max:255',
        ],[
            'slug.required' => 'введите урл скидки',
        ]);

        $local = env('APP_LOCALE', 'ua');

        DB::beginTransaction();

        $discount = Discount::create($request->all());

        $languages = Language::where('status', '1')->get();
        $discount->shops()->attach($request->shop);

        foreach ($languages as $lang) {

            $locale = $lang->locale;

            $discount_translate = new DiscountTranslate();
            $discount_translate->discount_id = $discount->id;
            $discount_translate->locale = $locale;
            $discount_translate->title = $request->{$locale}['title'];
            $discount_translate->save();
        }

        if ($request->file('import_file_products')) {

            $filename_data = fopen($request->file('import_file_products'), "r");
            $i = 0;
            $city_arr = array();

            while (($data = fgetcsv($filename_data, 1000, ";")) !== FALSE) {
                if($i != 0){

                    $number_str = $i + 1;
                    $prod_data = [
                        'slug' => $data[0] ? $this->translateToUrl($data[0], $local) : '',
                        'old_price' => $data[3] ? str_replace(',', '.', trim($data[3])) : 0,
                        'price' => $data[4] ? str_replace(',', '.', trim($data[4])) : 0,
                        'discount' => $data[5] ? str_replace(',', '.', trim($data[5])) : 0,
                        'quantity' => $data[1] ? str_replace(',', '.', trim($data[1])) : 0,
                        'unit' => $data[2] ? $data[2] : 0,
                    ];

                    $validator = Validator::make(
                        $prod_data,
                        [
                            'old_price' => 'regex:/^\d+(\.\d{1,2})?$/',
                            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                            'discount' => 'regex:/^\d+(\.\d{1,2})?$/',
                            'quantity' => 'required|regex:/^\d+(\.\d{1,3})?$/',
                            'unit' => 'string|in:kg,sht,l,up|max:255',
                        ],
                        [
                            'old_price.regex' => 'Ошибка старая цена в строке: '.$number_str,
                            'price.regex' => 'Ошибка новая цена в строке: '.$number_str,
                            'price.required' => 'Ошибка новая цена в строке: '.$number_str,
                            'discount' => 'Ошибка скидки в строке: '.$number_str,
                            'quantity.regex' => 'Ошибка количества в строке: '.$number_str,
                            'unit.in' => 'Ошибка указания еденица измерения в строке: '.$number_str,
                        ]
                    );

                    if ($validator->fails()) {
                        DB::rollBack();
                        return redirect()->route('admin.discounts.create')
                            ->withErrors($validator);
                    }

                    $product = Product::create($prod_data);
                    $image = explode('/', trim($data[6]));

                    if (count($image) > 1) {
                        $link = trim($data[6]);
                        $file_name = basename(trim($link));

                        $dirname = 'uploads/' . $discount->id.'/';

                        Storage::disk('public')->put($dirname . $file_name, file_get_contents($link));

                    }

                    $product->image = 'uploads/'.$discount->id.'/'.array_pop($image);

                    $product->save();
                    $product->discounts()->sync($discount->id);

                    foreach ($languages as $lang) {
                        $locale = $lang->locale;
                        $products_translate = new ProductTranslate();
                        $products_translate->product_id = $product->id;
                        $products_translate->locale = $locale;
                        $products_translate->title = $data[0];
                        $products_translate->save();
                    }
                }
                $i++;
            }

            fclose($filename_data);
        }

        if ($request->file('import_file_images')) {

            $dirname = storage_path('app/public/uploads/' . $discount->id);

            if (!is_dir($dirname)) {
                Storage::disk('public')->makeDirectory('/uploads/' . $discount->id);
            }

            $zip = new ZipArchive;

            if ($zip->open($request->file('import_file_images')) === TRUE) {
                $zip->extractTo($dirname);
                $zip->close();
            }

        }

        DB::commit();

        return redirect()
            ->route('admin.discounts.edit', $discount->id)->with('success', 'Акция успешно добавлена' );
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
                'product.*.price' => 'required',
            ],[
                'slug.required' => 'введите урл скидки',
                'product.*.price.required' => 'добавте новую цену товара',
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

                        $translate = [];

                        foreach ($languages as $lang) {
                            $locale = $lang->locale;
                            $prod_translate = ProductTranslate::where('product_id', $product_save->id);
                            $translate['locale']  = $locale;
                            $translate['title'] = $product[$locale]['title'];
                            $prod_translate->update($translate);
                        }

                        $slug = Product::where('slug', $product['slug'])->where('id', '<>', $product_save->id)->get();

                        while (count($slug) > 0) {
                            $product['slug'] = $product['slug'].'-'.rand(1, 15);
                            $product_save->slug = $product['slug'];
                            $product_save->save();
                            $slug2 = Product::where('slug', $product_save->slug)->where('id', '<>', $product_save->id)->get();
                            if (count($slug2) == 0 ) {
                                break;
                            }
                        }

                        $product_save->update($product);

                    } else {

                        $prod = Product::create($product);

                        array_push($product_not_delete, $prod->id);

                        if (!empty($product['image'])) {
                            $img = $product['image'];
                            $image = $img->store('uploads/' . $prod->id, 'public');
                            $prod->image = $image;
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

                        $slug = Product::where('slug', $product['slug'])->where('id', '<>', $prod->id)->get();

                        if (count($slug) == 0) {
                            $prod->slug = $product['slug'];
                            $prod->save();
                        } else {
                            while (count($slug) > 0) {
                                $product['slug'] = $product['slug'].'-'.rand(1, 15);
                                $prod->slug = $product['slug'];
                                $prod->save();
                                $slug2 = Product::where('slug', $prod->slug)->where('id', '<>', $prod->id)->get();
                                if (count($slug2) == 0 ) {
                                    break;
                                }
                            }
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

    public function translateToUrl($str, $loc){
        $converter = array(
            'ß' => 'ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
            'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
            'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'd',
            'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'ő' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 'ÿ' => 'y', 'α' => 'a', 'β' => 'b',
            'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3',
            'ο' => 'o', 'π' => 'p', 'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y',
            'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w', 'ά' => 'a', 'έ' => 'e',
            'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i', 'ş' => 's', 'ı' => 'i',
            'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 'а' => 'a', 'б' => 'b',
            'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm',
            'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh',
            'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya', 'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g', 'č' => 'c',
            'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't',
            'ů' => 'u', 'ž' => 'z', 'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l',
            'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z', 'ż' => 'z', 'ā' => 'a',
            'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l',
            'ņ' => 'n', 'š' => 's', 'ū' => 'u', 'ž' => 'z', 'ө' => 'o', 'ң' => 'n',
            'ү' => 'u', 'ә' => 'a', 'ғ' => 'g', 'қ' => 'q', 'ұ' => 'u', 'ა' => 'a',
            'ბ' => 'b', 'გ' => 'g', 'დ' => 'd', 'ე' => 'e', 'ვ' => 'v', 'ზ' => 'z',
            'თ' => 'th', 'ი' => 'i', 'კ' => 'k', 'ლ' => 'l', 'მ' => 'm', 'ნ' => 'n',
            'ო' => 'o', 'პ' => 'p', 'ჟ' => 'zh', 'რ' => 'r', 'ს' => 's', 'ტ' => 't',
            'უ' => 'u', 'ფ' => 'ph', 'ქ' => 'q', 'ღ' => 'gh', 'ყ' => 'qh', 'შ' => 'sh',
            'ჩ' => 'ch', 'ც' => 'ts', 'ძ' => 'dz', 'წ' => 'ts', 'ჭ' => 'tch', 'ხ' => 'kh',
            'ჯ' => 'j', 'ჰ' => 'h'
        );

        switch($loc){
            case'bg':
                $converter['щ'] = 'sht';
                $converter['ъ'] = 'a';
                break;
            case'ua':
                $converter['и'] = 'y';
                break;
        }

        $string = strtr($str, $converter);
        $string = mb_strtolower($string);
        $string = str_replace(' ', '-', $string);
        $string = str_replace('"', '', $string);
        $string = str_replace(',', '', $string);
        $string = trim($string, "-");

        return $string;
    }

    public function uniqueUrl($id, $slug, $table)
    {
        $slugs = DB::table($table)->where('slug', $slug)->where('id', '<>', $id)->get();
        if (count($slugs) > 0) {

            while (count($slugs) > 0) {
                $slug_new = $slug.'-'.rand(1, 15);
                $slug2 = DB::table($table)->where('slug', $slug_new)->where('id', '<>', $id)->get();
                if (count($slug2) == 0 ) {
                    break;
                }
            }

        }else {
            $slug_new = $slug;
        }

        return $slug_new;
    }

}
